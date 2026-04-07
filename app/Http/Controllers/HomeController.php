<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::featured()->active()->latest()->take(8)->get();
        $latestArticles = Article::published()->latest()->take(6)->get();
        $categories = Category::active()->ordered()->get();
        
        // Fishery Statistics for Charts
        $latestYear = \App\Models\FisheryStatistic::max('year') ?? date('Y');
        $stats = \App\Models\FisheryStatistic::where('year', $latestYear)->get();
        
        $cities = ['Samarinda', 'Bontang', 'Balikpapan', 'Kutai Kartanegara', 'Kutai Timur', 'Berau', 'Paser'];
        
        $getChartData = function($column) use ($stats, $cities) {
            $data = [];
            $otherCount = 0;
            foreach ($cities as $city) {
                $stat = $stats->firstWhere('regency_city', $city);
                $data[] = $stat ? ($stat->{$column} ?? 0) : 0;
            }
            // For cities not in main labels
            $otherCities = ['Kutai Barat', 'Penajam Paser Utara', 'Mahakam Ulu'];
            foreach($otherCities as $oc) {
                $stat = $stats->firstWhere('regency_city', $oc);
                if ($stat) $otherCount += ($stat->{$column} ?? 0);
            }
            $data[] = $otherCount;
            return $data;
        };

        $chartData = [
            'labels' => ['Samarinda', 'Bontang', 'Balikpapan', 'Kukar', 'Kutim', 'Berau', 'Paser', 'Lainnya'],
            'fish' => $getChartData('fish_farmer_count'),
            'shrimp' => $getChartData('shrimp_farmer_count'),
            'fisherman' => $getChartData('fisherman_count'),
            'others' => [
                (int)$stats->sum('crab_farmer_count'),
                (int)$stats->sum('seaweed_farmer_count'),
                (int)$stats->sum('clam_farmer_count'),
                (int)$stats->sum('lobster_farmer_count'),
                (int)$stats->sum('abalone_farmer_count'),
                (int)$stats->sum('sea_cucumber_farmer_count'),
                (int)$stats->sum('other_farmer_count'),
            ],
            'year' => $latestYear
        ];
        
        return view('home', compact('featuredProducts', 'latestArticles', 'categories', 'chartData'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function handleContact(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone ?? '-';
        $subject = $request->subject;
        $message = $request->message;

        // Ambil nomor WhatsApp dari pengaturan
        $waNumber = get_setting('whatsapp_number', '6281234567890');
        
        // Bersihkan nomor (hanya angka)
        $waNumber = preg_replace('/[^0-9]/', '', $waNumber);
        
        // Jika dimulai dengan 0, ganti dengan 62
        if (strpos($waNumber, '0') === 0) {
            $waNumber = '62' . substr($waNumber, 1);
        }

        // Susun pesan WhatsApp
        $waMessage = "*Pesan Baru dari Form Kontak - FISHERIES*\n";
        $waMessage .= "---------------------------------------\n";
        $waMessage .= "*Nama:* {$name}\n";
        $waMessage .= "*Email:* {$email}\n";
        $waMessage .= "*Telepon:* {$phone}\n";
        $waMessage .= "*Subjek:* {$subject}\n\n";
        $waMessage .= "*Pesan:*\n{$message}\n";
        $waMessage .= "---------------------------------------";

        // Buat URL WhatsApp
        $url = "https://wa.me/{$waNumber}?text=" . urlencode($waMessage);

        // Redirect ke WhatsApp
        return redirect()->away($url);
    }

    public function partnership()
    {
        return view('partnership');
    }
}
