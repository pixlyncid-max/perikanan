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
        
        return view('home', compact('featuredProducts', 'latestArticles', 'categories'));
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
