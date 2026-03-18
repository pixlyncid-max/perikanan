<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        $settings = $this->getSettings();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $settings = $request->except(['_token', '_method']);
        
        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $logo = $request->file('site_logo');
            $logoName = 'logo.' . $logo->getClientOriginalExtension();
            $logo->storeAs('public/settings', $logoName);
            $settings['site_logo'] = 'settings/' . $logoName;
        }
        
        // Handle favicon upload
        if ($request->hasFile('site_favicon')) {
            $favicon = $request->file('site_favicon');
            $faviconName = 'favicon.' . $favicon->getClientOriginalExtension();
            $favicon->storeAs('public/settings', $faviconName);
            $settings['site_favicon'] = 'settings/' . $faviconName;
        }
        
        // Save settings to JSON file
        Storage::put('settings.json', json_encode($settings, JSON_PRETTY_PRINT));
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Get settings from storage.
     */
    private function getSettings()
    {
        if (Storage::exists('settings.json')) {
            return json_decode(Storage::get('settings.json'), true);
        }
        
        // Default settings
        return [
            // Site Information
            'site_name' => 'FISHERIES',
            'site_tagline' => 'Forum Komunikasi Perikanan Indonesia',
            'site_description' => 'Website resmi FISHERIES - Forum Komunikasi Perikanan Indonesia',
            'site_email' => 'info@fisheries.id',
            'site_phone' => '(0541) 123456',
            'site_address' => 'Jl. Delima Dalam Blok E, Sidodadi, Kec. Samarinda Ulu, Kota Samarinda, Kalimantan Timur 75243',
            
            // Social Media
            'facebook_url' => '',
            'tiktok_url' => '',
            'instagram_url' => '',
            'youtube_url' => '',
            'whatsapp_number' => '',
            
            // Home Page Settings
            'hero_title' => 'Indonesian Fisheries Community',
            'hero_subtitle' => 'Komunitas perikanan terbesar di Kalimantan Timur. Menghubungkan nelayan, pembudidaya, dan pelaku usaha perikanan.',
            'hero_button1_text' => 'Lihat Produk',
            'hero_button1_url' => '/produk',
            'hero_button2_text' => 'Gabung Sekarang',
            'hero_button2_url' => '/register',
            
            // Statistics
            'stats_members' => '2000+',
            'stats_members_label' => 'Anggota Aktif',
            'stats_dpc' => '10',
            'stats_dpc_label' => 'DPC Kaltim',
            'stats_products' => '50+',
            'stats_products_label' => 'Produk Unggulan',
            'stats_partners' => '500+',
            'stats_partners_label' => 'Mitra Bisnis',
            
            // About Page
            'about_title' => 'Tentang FISHERIES',
            'about_content' => 'FISHERIES adalah forum komunikasi perikanan terbesar di Indonesia yang menghubungkan nelayan, pembudidaya, dan pelaku usaha perikanan untuk menciptakan ekosistem yang berkelanjutan.',
            'about_vision' => 'Menjadi komunitas perikanan terbesar dan terpercaya di Indonesia yang menghubungkan seluruh pelaku usaha perikanan untuk menciptakan ekosistem perikanan yang berkelanjutan dan menguntungkan bagi semua pihak.',
            'about_mission' => 'Membangun jaringan nelayan dan pembudidaya yang kuat di seluruh Indonesia;Menyediakan akses ke produk berkualitas dengan harga terjangkau;Meningkatkan kapasitas anggota melalui pelatihan dan edukasi;Mendorong inovasi teknologi dalam bidang perikanan',
            
            // Partnership Page
            'partnership_title' => 'Kerjasama & Partnership',
            'partnership_description' => 'FISHERIES membuka peluang kerjasama dengan berbagai pihak untuk bersama-sama membangun ekosistem perikanan yang kuat dan berkelanjutan.',
            'partnership_content' => 'Kami mengundang perusahaan, institusi, dan individu yang memiliki visi sama untuk berkolaborasi dalam pengembangan perikanan berkelanjutan.',
            
            // Contact Page
            'contact_title' => 'Hubungi Kami',
            'contact_description' => 'Kami siap membantu Anda. Hubungi kami melalui berbagai kanal komunikasi yang tersedia.',
            'contact_business_hours' => 'Senin - Jumat: 08.00 - 17.00;Sabtu: 08.00 - 12.00;Minggu: Tutup',
            
            // Footer
            'footer_copyright' => '© 2026 FISHERIES. All rights reserved.',
            'footer_description' => 'Forum Komunikasi Perikanan Indonesia - Membangun ekosistem perikanan yang berkelanjutan.',
        ];
    }
}
?>
