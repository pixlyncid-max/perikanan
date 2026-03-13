<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Teknik Budidaya Ikan Lele Modern',
                'slug' => 'teknik-budidaya-ikan-lele-modern',
                'content' => 'Budidaya ikan lele modern menggunakan sistem bioflok dan RAS (Recirculating Aquaculture System) semakin populer di kalangan petani ikan. Teknik ini memungkinkan produksi yang lebih tinggi dengan penggunaan air yang lebih sedikit...',
                'excerpt' => 'Pelajari teknik budidaya ikan lele modern dengan sistem bioflok untuk hasil maksimal.',
                'featured_image' => 'articles/lele-bioflok.jpg',
                'status' => 'published',
                'author_id' => 1,
                'published_at' => now(),
            ],
            [
                'title' => 'Panduan Memilih Pakan Ikan Berkualitas',
                'slug' => 'panduan-memilih-pakan-ikan-berkualitas',
                'content' => 'Pemilihan pakan ikan yang tepat sangat penting untuk pertumbuhan dan kesehatan ikan. Pakan berkualitas harus memiliki kandungan protein yang cukup, vitamin, dan mineral yang seimbang...',
                'excerpt' => 'Tips memilih pakan ikan berkualitas untuk pertumbuhan optimal.',
                'featured_image' => 'articles/pakan-ikan.jpg',
                'status' => 'published',
                'author_id' => 1,
                'published_at' => now(),
            ],
            [
                'title' => 'Cara Mengatasi Penyakit Ikan Nila',
                'slug' => 'cara-mengatasi-penyakit-ikan-nila',
                'content' => 'Ikan nila rentan terhadap beberapa penyakit seperti white spot, columnaris, dan infeksi bakteri. Pencegahan melalui sanitasi kolam yang baik dan pemberian pakan berkualitas adalah kunci utama...',
                'excerpt' => 'Panduan lengkap mengatasi penyakit umum pada ikan nila.',
                'featured_image' => 'articles/penyakit-nila.jpg',
                'status' => 'published',
                'author_id' => 2,
                'published_at' => now(),
            ],
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}
