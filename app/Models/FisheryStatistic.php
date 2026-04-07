<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FisheryStatistic extends Model
{
    protected $fillable = [
        'regency_city',
        'year',
        'fish_farmer_count',
        'shrimp_farmer_count',
        'fisherman_count',
        'production_volume',
        'production_value',
        'area_size',
        'main_commodities',
        'crab_farmer_count',
        'seaweed_farmer_count',
        'clam_farmer_count',
        'lobster_farmer_count',
        'abalone_farmer_count',
        'sea_cucumber_farmer_count',
        'other_farmer_count',
    ];
}
