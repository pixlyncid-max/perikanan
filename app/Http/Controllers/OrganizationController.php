<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class OrganizationController extends Controller
{
    private function getAnggotaList()
    {
        return [
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
            ]
        ];
    }

    public function dpp()
    {
        $dpp = Organization::where('type', 'dpp')->where('is_active', true)->first();
        
        $ketuaUmum = [
            'name' => $dpp->chairman ?? 'Dr. H. Ahmad Sudirman, M.Si',
            'position' => 'Ketua Umum',
            'organization' => $dpp->name ?? 'DPP FISHERIES Indonesia',
            'photo' => null,
            'full_name' => $dpp->chairman ?? 'Dr. H. Ahmad Sudirman, M.Si',
            'field' => 'Budidaya Perikanan Air Tawar',
            'location' => 'Jakarta Pusat, DKI Jakarta',
            'experience' => '20+ tahun di bidang perikanan'
        ];

        $dpcs = Organization::where('type', 'dpc')
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();

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

        $scope = 'Pusat';
        $regionName = 'Pusat';
        $anggotaList = $this->getAnggotaList();
        
        return view('organization.structure', compact('scope', 'regionName', 'ketuaUmum', 'ketuaDpcList', 'anggotaList'));
    }

    public function dpwIndex()
    {
        $provinces = array_keys(config('regions'));
        return view('organization.index-dpw', compact('provinces'));
    }

    public function dpwShow($province)
    {
        $provinceName = ucwords(str_replace('-', ' ', $province));
        
        $ketuaUmum = [
            'name' => 'Ketua DPW ' . $provinceName,
            'position' => 'Ketua DPW',
            'organization' => 'DPW FISHERIES ' . $provinceName,
            'photo' => null,
            'full_name' => 'Ketua DPW ' . $provinceName,
            'field' => 'Perikanan ' . $provinceName,
            'location' => $provinceName,
            'experience' => 'Tokoh perikanan wilayah'
        ];

        $scope = 'Wilayah';
        $regionName = $provinceName;
        // DPW hanya menampilkan struktur inti (ketuaUmum, tanpa daftar panjang anggotaList)
        $anggotaList = [];
        
        return view('organization.structure', compact('scope', 'regionName', 'ketuaUmum', 'anggotaList'));
    }

    public function dpcIndex()
    {
        $regions = config('regions');
        return view('organization.index-dpc', compact('regions'));
    }

    public function dpcShow($province, $city)
    {
        $provinceName = ucwords(str_replace('-', ' ', $province));
        $cityName = ucwords(str_replace('-', ' ', $city));
        
        $ketuaUmum = [
            'name' => 'Ketua DPC ' . $cityName,
            'position' => 'Ketua DPC',
            'organization' => 'DPC FISHERIES ' . $cityName,
            'photo' => null,
            'full_name' => 'Ketua DPC ' . $cityName,
            'field' => 'Perikanan Cabang',
            'location' => $cityName . ', ' . $provinceName,
            'experience' => 'Tokoh perikanan cabang'
        ];

        $scope = 'Cabang';
        $regionName = $cityName;
        // DPC hanya menampilkan struktur inti
        $anggotaList = [];
        
        return view('organization.structure', compact('scope', 'regionName', 'ketuaUmum', 'anggotaList'));
    }
}
