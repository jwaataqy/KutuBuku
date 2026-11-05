<?php

namespace Database\Seeders;

use App\Models\{Category, Product, User, Voucher};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'Demo Pembaca', 'email' => 'demo@kutubuku.test', 'password' => 'password']);
        Voucher::create(['code' => 'HEMAT10', 'percent' => 10]);
        Voucher::create(['code' => 'KUTU25', 'percent' => 25]);
        $cats = collect(['Fiksi', 'Bisnis', 'Teknologi', 'Pengembangan Diri', 'Sains', 'Sejarah'])->map(fn($n) => Category::create(['name' => $n, 'slug' => Str::slug($n)]));
        $books = [ // judul, penulis, kategori, harga, diskon, format, terjual, unggulan
            ['Senja di Kota Lama', 'Ratna Wulandari', 0, 59000, 45000, 'EPUB', 320, 1],
            ['Laravel dari Nol hingga Mahir', 'Dimas Pratama', 2, 99000, null, 'PDF', 540, 1],
            ['Seni Berbisnis Digital', 'Almira Syahrul', 1, 89000, 69000, 'PDF', 410, 0],
            ['Atomic Mind', 'Kevin Hartono', 3, 75000, null, 'EPUB', 280, 1],
            ['Panduan Gratis Belajar SQL', 'Tim Kutubuku', 2, 0, null, 'PDF', 900, 0],
            ['Kosmos dan Kita', 'Dr. Ayu Lestari', 4, 85000, 65000, 'PDF', 150, 0],
            ['Nusantara dalam Lintasan Waktu', 'Bambang Setiadi', 5, 79000, null, 'EPUB', 190, 0],
            ['Hujan yang Tak Pernah Pulang', 'Salsabila Putri', 0, 49000, null, 'EPUB', 360, 1],
            ['Bootcamp UI/UX Praktis', 'Nadia Kusuma', 2, 119000, 89000, 'ZIP', 230, 0],
            ['Mulai Usaha dari Rumah', 'Fajar Nugroho', 1, 65000, null, 'PDF', 270, 0],
            ['Fokus yang Tenang', 'Intan Maharani', 3, 0, null, 'PDF', 610, 1],
            ['Misteri Menara Biru', 'Rizky Aditya', 0, 55000, 39000, 'EPUB', 205, 0],
        ];
        foreach ($books as [$t, $a, $c, $p, $d, $f, $s, $ft]) Product::create([
            'title' => $t,
            'slug' => Str::slug($t),
            'author' => $a,
            'category_id' => $cats[$c]->id,
            'price' => $p,
            'discount_price' => $d,
            'format' => $f,
            'sold' => $s,
            'featured' => $ft,
            'file_path' => 'ebooks/' . Str::slug($t) . '.' . strtolower($f),
            'description' => "$t adalah karya $a yang dikemas ringan namun mendalam. Cocok dibaca di mana saja, dengan bahasa yang mengalir dan contoh yang dekat dengan keseharian pembaca.",
            'preview' => "Bab 1 — Awal Mula\n\nPagi itu, segalanya terasa biasa saja. Namun ada sesuatu yang diam-diam berubah: cara kita memandang halaman pertama. Setiap buku besar selalu dimulai dari satu kalimat sederhana, dan kalimat itulah yang akan mengajakmu masuk lebih dalam...\n\n(Pratinjau berakhir. Beli untuk membaca selengkapnya.)",
        ]);
    }
}
