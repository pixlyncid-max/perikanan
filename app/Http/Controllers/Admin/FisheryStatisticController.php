<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FisheryStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FisheryStatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FisheryStatistic::query();

        // Search functionality
        if ($request->has('search') && $request->get('search') !== '') {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('regency_city', 'like', "%{$search}%")
                  ->orWhere('year', 'like', "%{$search}%")
                  ->orWhere('main_commodities', 'like', "%{$search}%");
            });
        }

        // Filter by regency_city
        if ($request->has('regency_city') && $request->get('regency_city') !== '') {
            $query->where('regency_city', $request->get('regency_city'));
        }

        // Filter by year
        if ($request->has('year') && $request->get('year') !== '') {
            $query->where('year', $request->get('year'));
        }

        $statistics = $query->orderBy('year', 'desc')->orderBy('regency_city', 'asc')->paginate(15);
        
        $regencies = [
            'Samarinda', 'Balikpapan', 'Bontang', 'Kutai Kartanegara', 
            'Kutai Timur', 'Kutai Barat', 'Berau', 'Paser', 
            'Penajam Paser Utara', 'Mahakam Ulu'
        ];

        return view('admin.fishery_statistics.index', compact('statistics', 'regencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regencies = [
            'Samarinda', 'Balikpapan', 'Bontang', 'Kutai Kartanegara', 
            'Kutai Timur', 'Kutai Barat', 'Berau', 'Paser', 
            'Penajam Paser Utara', 'Mahakam Ulu'
        ];
        return view('admin.fishery_statistics.create', compact('regencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'regency_city' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'fish_farmer_count' => 'nullable|integer|min:0',
            'shrimp_farmer_count' => 'nullable|integer|min:0',
            'fisherman_count' => 'nullable|integer|min:0',
            'crab_farmer_count' => 'nullable|integer|min:0',
            'seaweed_farmer_count' => 'nullable|integer|min:0',
            'clam_farmer_count' => 'nullable|integer|min:0',
            'lobster_farmer_count' => 'nullable|integer|min:0',
            'abalone_farmer_count' => 'nullable|integer|min:0',
            'sea_cucumber_farmer_count' => 'nullable|integer|min:0',
            'other_farmer_count' => 'nullable|integer|min:0',
            'production_volume' => 'nullable|numeric|min:0',
            'production_value' => 'nullable|numeric|min:0',
            'area_size' => 'nullable|numeric|min:0',
            'main_commodities' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        FisheryStatistic::create($request->all());

        return redirect()->route('admin.fishery-statistics.index')
            ->with('success', 'Data statistik berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FisheryStatistic $fisheryStatistic)
    {
        return view('admin.fishery_statistics.show', compact('fisheryStatistic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FisheryStatistic $fisheryStatistic)
    {
        $regencies = [
            'Samarinda', 'Balikpapan', 'Bontang', 'Kutai Kartanegara', 
            'Kutai Timur', 'Kutai Barat', 'Berau', 'Paser', 
            'Penajam Paser Utara', 'Mahakam Ulu'
        ];
        return view('admin.fishery_statistics.edit', compact('fisheryStatistic', 'regencies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FisheryStatistic $fisheryStatistic)
    {
        $validator = Validator::make($request->all(), [
            'regency_city' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'fish_farmer_count' => 'nullable|integer|min:0',
            'shrimp_farmer_count' => 'nullable|integer|min:0',
            'fisherman_count' => 'nullable|integer|min:0',
            'crab_farmer_count' => 'nullable|integer|min:0',
            'seaweed_farmer_count' => 'nullable|integer|min:0',
            'clam_farmer_count' => 'nullable|integer|min:0',
            'lobster_farmer_count' => 'nullable|integer|min:0',
            'abalone_farmer_count' => 'nullable|integer|min:0',
            'sea_cucumber_farmer_count' => 'nullable|integer|min:0',
            'other_farmer_count' => 'nullable|integer|min:0',
            'production_volume' => 'nullable|numeric|min:0',
            'production_value' => 'nullable|numeric|min:0',
            'area_size' => 'nullable|numeric|min:0',
            'main_commodities' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $fisheryStatistic->update($request->all());

        return redirect()->route('admin.fishery-statistics.index')
            ->with('success', 'Data statistik berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FisheryStatistic $fisheryStatistic)
    {
        $fisheryStatistic->delete();

        return redirect()->route('admin.fishery-statistics.index')
            ->with('success', 'Data statistik berhasil dihapus.');
    }
}
