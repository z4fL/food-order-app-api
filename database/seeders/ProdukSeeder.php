<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produk::insert([
            [
                'nama' => 'Ayam Goreng Lengkuas',
                'harga' => 18000,
                'kategori' => 'makanan',
                'gambar' => 'images/ayam-goreng.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ayam Bakar Presto',
                'harga' => 20000,
                'kategori' => 'makanan',
                'gambar' => 'images/ayam-bakar.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Bakso',
                'harga' => 15000,
                'kategori' => 'makanan',
                'gambar' => 'images/bakso.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Mie Ayam',
                'harga' => 14000,
                'kategori' => 'makanan',
                'gambar' => 'images/mie-ayam.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Nasi Goreng',
                'harga' => 18000,
                'kategori' => 'makanan',
                'gambar' => 'images/nasi-goreng.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pecel Lele',
                'harga' => 17000,
                'kategori' => 'makanan',
                'gambar' => 'images/pecel-lele.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Soto Ayam',
                'harga' => 15000,
                'kategori' => 'makanan',
                'gambar' => 'images/soto-ayam.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Rawon',
                'harga' => 25000,
                'kategori' => 'makanan',
                'gambar' => 'images/rawon.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Gudeg',
                'harga' => 20000,
                'kategori' => 'makanan',
                'gambar' => 'images/gudeg.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Sate Ayam',
                'harga' => 20000,
                'kategori' => 'makanan',
                'gambar' => 'images/sate-ayam.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Es Teh',
                'harga' => 4000,
                'kategori' => 'minuman',
                'gambar' => 'images/es-teh.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Es Jeruk',
                'harga' => 5000,
                'kategori' => 'minuman',
                'gambar' => 'images/es-jeruk.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Es Alpukat',
                'harga' => 12000,
                'kategori' => 'minuman',
                'gambar' => 'images/es-alpukat.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kopi Hitam',
                'harga' => 6000,
                'kategori' => 'minuman',
                'gambar' => 'images/kopi-hitam.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Es Milo',
                'harga' => 10000,
                'kategori' => 'minuman',
                'gambar' => 'images/es-milo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Susu Jahe',
                'harga' => 8000,
                'kategori' => 'minuman',
                'gambar' => 'images/susu-jahe.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Teh Hangat',
                'harga' => 3000,
                'kategori' => 'minuman',
                'gambar' => 'images/teh-hangat.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Air Mineral Botol',
                'harga' => 4000,
                'kategori' => 'minuman',
                'gambar' => 'images/air-mineral-botol.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
