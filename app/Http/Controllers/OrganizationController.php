<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class OrganizationController extends Controller

{
    public function structure()
    {
        $dpp = Organization::where('type', 'dpp')->where('is_active', true)->first();
        $dpcs = Organization::where('type', 'dpc')
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();

        // Data Ketua Umum
        $ketuaUmum = [
            'name' => $dpp->chairman ?? 'Dr. H. Ahmad Sudirman, M.Si',
            'position' => 'Ketua Umum',
            'organization' => $dpp->name ?? 'DPP FISHERIES Indonesia',
            'photo' => null,
            'full_name' => $dpp->chairman ?? 'Dr. H. Ahmad Sudirman, M.Si',
            'field' => 'Budidaya Perikanan Air Tawar',
            'location' => 'Samarinda, Kalimantan Timur',
            'experience' => '20+ tahun di bidang perikanan'
        ];

        // Data Ketua DPC
        $ketuaDpcList = [];
        foreach ($dpcs as $dpc) {
            $ketuaDpcList[] = [
                'name' => $dpc->chairman ?? 'Ketua DPC ' . $dpc->city,
                'position' => 'Ketua DPC',
                'region' => $dpc->city,
                'photo' => null,
                'full_name' => $dpc->chairman ?? 'Ketua DPC ' . $dpc->city,
                'field' => 'Perikanan ' . $dpc->city,
                'location' => $dpc->city . ', Kalimantan Timur',
                'member_count' => $dpc->member_count ?? 0
            ];
        }

        // Data Anggota (sample data - in production would come from Member model)
        $anggotaList = [
            [
                'name' => 'Budi Santoso',
                'position' => 'Anggota Pembudidaya',
                'photo' => null,
                'full_name' => 'Budi Santoso, S.Pi',
                'field' => 'Pembesaran Lele',
                'location' => 'Samarinda'
            ],
            [
                'name' => 'Dewi Kusuma',
                'position' => 'Anggota Pembudidaya',
                'photo' => null,
                'full_name' => 'Dewi Kusuma, S.Pi',
                'field' => 'Budidaya Udang Vaname',
                'location' => 'Balikpapan'
            ],
            [
                'name' => 'Eko Prasetyo',
                'position' => 'Anggota Nelayan',
                'photo' => null,
                'full_name' => 'Eko Prasetyo',
                'field' => 'Penangkapan Ikan Laut',
                'location' => 'Bontang'
            ],
            [
                'name' => 'Fitriani Rahayu',
                'position' => 'Anggota Pengolah',
                'photo' => null,
                'full_name' => 'Fitriani Rahayu',
                'field' => 'Pengolahan Hasil Perikanan',
                'location' => 'Samarinda'
            ],
            [
                'name' => 'Gunawan Wibowo',
                'position' => 'Anggota Pembudidaya',
                'photo' => null,
                'full_name' => 'Gunawan Wibowo, S.Pi',
                'field' => 'Budidaya Ikan Nila',
                'location' => 'Kutai Kartanegara'
            ],
            [
                'name' => 'Hani Susanti',
                'position' => 'Anggota Pembudidaya',
                'photo' => null,
                'full_name' => 'Hani Susanti',
                'field' => 'Budidaya Ikan Gurame',
                'location' => 'Berau'
            ]
        ];

        return view('organization.structure', compact('dpp', 'dpcs', 'ketuaUmum', 'ketuaDpcList', 'anggotaList'));
    }


    public function showDpc($code)
    {
        // Map URL slug to city name (matching navbar links)
        $cityMap = [
            'samarinda' => 'Samarinda',
            'balikpapan' => 'Balikpapan',
            'bontang' => 'Bontang',
            'berau' => 'Berau',
            'kutai-kartanegara' => 'Kutai Kartanegara',
            'paser' => 'Paser',
            'penajam-paser-utara' => 'Penajam Paser Utara',
            'kutai-barat' => 'Kutai Barat',
            'kutai-timur' => 'Kutai Timur',
            'mahakam-ulu' => 'Mahakam Ulu',
        ];

        
        $city = $code;
        if (isset($cityMap[$code])) {
            $city = $cityMap[$code];
        }
        
        // Ensure city is a string
        $city = (string) $city;
        
        $dpc = Organization::where('city', $city)
            ->where('type', 'dpc')
            ->where('is_active', true)
            ->firstOrFail();

        
        // Return specific view based on city code
        $viewName = 'organization.dpc_' . $code;
        if (!View::exists($viewName)) {
            $viewName = 'organization.dpc_samarinda';
        }

        
        return view($viewName, compact('dpc'));
    }

}
