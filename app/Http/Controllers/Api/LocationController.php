<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Location;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    /**
     * Get locations that can fulfill the products.
     * Request body: { items: [{ product_id: 1, quantity: 2 }, ...] }
     */
    public function getValidLocations(Request $request)
    {
        $items = $request->input('items', []);
        $locations = $this->findLocationsForItems($items);

        if ($locations->isEmpty()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $this->getMissingMessage($items)
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }

    /**
     * Get nearest valid location.
     * Request body: { lat: -6.2, lng: 106.8, items: [...] }
     */
    public function getNearestLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = (float) $request->lat;
        $lng = (float) $request->lng;
        $items = $request->input('items', []);

        $locations = $this->findLocationsForItems($items);

        if ($locations->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMissingMessage($items)
            ], 404);
        }

        // Calculate Haversine distance
        $nearest = null;
        $minDistance = null;

        foreach ($locations as $location) {
            $distance = $this->haversineGreatCircleDistance(
                $lat, $lng, 
                (float)$location->latitude, (float)$location->longitude
            );

            // Add distance to object for frontend info
            $location->distance_km = round($distance, 2);

            if ($minDistance === null || $distance < $minDistance) {
                $minDistance = $distance;
                $nearest = $location;
            }
        }

        return response()->json([
            'success' => true,
            'data' => $nearest
        ]);
    }

    private function findLocationsForItems($items)
    {
        if (empty($items)) {
            return collect();
        }

        $productIds = collect($items)->pluck('product_id')->toArray();

        // Start with all locations
        $query = Location::with(['products' => function($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        }]);

        // For each item, ensure the location has the product in stock using whereHas
        foreach ($items as $item) {
            $query->whereHas('products', function ($q) use ($item) {
                $q->where('product_id', $item['product_id'])
                  ->where('stok', '>=', $item['quantity'] ?? 1);
            });
        }

        $locations = $query->get();

        foreach($locations as $loc) {
            $loc->available_stock = $loc->products->sum('pivot.stok');
        }

        return $locations;
    }

    private function getMissingMessage($items)
    {
        if (empty($items)) return 'Keranjang kosong.';

        $missingProductNames = [];
        foreach ($items as $item) {
            $hasIndividually = Location::whereHas('products', function ($q) use ($item) {
                $q->where('product_id', $item['product_id'])
                  ->where('stok', '>=', $item['quantity'] ?? 1);
            })->exists();

            if (!$hasIndividually) {
                $product = \App\Models\Product::find($item['product_id']);
                if ($product) {
                    $missingProductNames[] = $product->name;
                }
            }
        }

        if (count($missingProductNames) > 0) {
            return 'Produk berikut kurang stok atau kosong di semua cabang saat ini: ' . implode(', ', $missingProductNames);
        }

        return 'Produk-produk pesanan Anda tersedia, namun tidak ada satu cabang pun yang memiliki seluruh item lengkap sekaligus untuk disatukan dalam satu pengiriman. Solusi: Silakan Checkout produk-produk ini secara terpisah (pecah menjadi beberapa pesanan berselang) agar dapat dikirim dari cabang yang berbeda.';
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @return float Distance in kilometers
     */
    private function haversineGreatCircleDistance($latFrom, $lonFrom, $latTo, $lonTo, $earthRadius = 6371)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latFrom);
        $lonFrom = deg2rad($lonFrom);
        $latTo = deg2rad($latTo);
        $lonTo = deg2rad($lonTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        
        return $angle * $earthRadius;
    }
}
