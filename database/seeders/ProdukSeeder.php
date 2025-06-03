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
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921127/ayam-goreng_nrwh6f.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ayam Bakar Presto',
                'harga' => 20000,
                'kategori' => 'makanan',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921127/ayam-bakar_imtxal.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Bakso',
                'harga' => 15000,
                'kategori' => 'makanan',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921127/bakso_eum6gd.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Mie Ayam',
                'harga' => 14000,
                'kategori' => 'makanan',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921129/mie-ayam_pvrifb.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Nasi Goreng',
                'harga' => 18000,
                'kategori' => 'makanan',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921133/nasi-goreng_ebfzcm.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pecel Lele',
                'harga' => 17000,
                'kategori' => 'makanan',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921132/pecel-lele_dvj4cn.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Soto Ayam',
                'harga' => 15000,
                'kategori' => 'makanan',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921133/soto-ayam_iqgwts.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Rawon',
                'harga' => 25000,
                'kategori' => 'makanan',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921133/rawon_nklrmf.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Gudeg',
                'harga' => 20000,
                'kategori' => 'makanan',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921132/gudeg_awj3r8.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Sate Ayam',
                'harga' => 20000,
                'kategori' => 'makanan',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921132/sate-ayam_ehokvb.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Es Teh',
                'harga' => 4000,
                'kategori' => 'minuman',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921131/es-teh_lcjdzi.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Es Jeruk',
                'harga' => 5000,
                'kategori' => 'minuman',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921130/es-jeruk_odsyto.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Es Alpukat',
                'harga' => 12000,
                'kategori' => 'minuman',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921128/es-alpukat_viz1nh.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kopi Hitam',
                'harga' => 6000,
                'kategori' => 'minuman',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921129/kopi-hitam_ylyx0e.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Es Milo',
                'harga' => 10000,
                'kategori' => 'minuman',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921127/es-milo_kpiezk.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Susu Jahe',
                'harga' => 8000,
                'kategori' => 'minuman',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921133/susu-jahe_s6lvb2.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Teh Hangat',
                'harga' => 3000,
                'kategori' => 'minuman',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921133/teh-hangat_jfityr.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Air Mineral Botol',
                'harga' => 4000,
                'kategori' => 'minuman',
                'gambar' => 'https://res.cloudinary.com/dsrrjwffj/image/upload/v1748921133/air-mineral-botol_b7xd9z.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
