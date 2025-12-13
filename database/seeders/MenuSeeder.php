<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            [
                'name' => 'Rendang Sapi',
                'description' => 'Daging sapi empuk dengan bumbu rempah khas',
                'price' => 35000.00,
                'category' => 'Daging',
                'image' => '/assets/images/Rendang_padang.jpeg',
                'is_available' => true,
                'is_popular' => true,
                'stock' => 100,
            ],
            [
                'name' => 'Gulai Ayam',
                'description' => 'Ayam kampung dengan kuah gulai gurih',
                'price' => 28000.00,
                'category' => 'Ayam',
                'image' => '/assets/images/Gulai_Ayam.jpeg',
                'is_available' => true,
                'is_popular' => true,
                'stock' => 100,
            ],
            [
                'name' => 'Dendeng Balado',
                'description' => 'Daging tipis kering dengan sambal balado',
                'price' => 32000.00,
                'category' => 'Daging',
                'image' => '/assets/images/Dendeng_Balado.jpeg',
                'is_available' => true,
                'is_popular' => true,
                'stock' => 100,
            ],
            [
                'name' => 'Sambal Ijo',
                'description' => 'Sambal hijau pedas khas Padang',
                'price' => 15000.00,
                'category' => 'Sambal',
                'image' => '/assets/images/Sambal_Ijo.jpeg',
                'is_available' => true,
                'is_popular' => true,
                'stock' => 100,
            ],
            [
                'name' => 'Ikan Kakap Goreng',
                'description' => 'Ikan kakap segar goreng garing',
                'price' => 45000.00,
                'category' => 'Ikan',
                'image' => '/assets/images/Ikan_Kakap.jpeg',
                'is_available' => true,
                'is_popular' => true,
                'stock' => 100,
            ],
            [
                'name' => 'Sayur Nangka',
                'description' => 'Nangka muda dengan kuah santan',
                'price' => 18000.00,
                'category' => 'Sayur',
                'image' => '/assets/images/Nangka.jpeg',
                'is_available' => true,
                'is_popular' => true,
                'stock' => 100,
            ],
            [
                'name' => 'Ayam Pop',
                'description' => 'Ayam goreng khas Padang yang lembut',
                'price' => 30000.00,
                'category' => 'Ayam',
                'image' => '/assets/images/ayampop.jpg',
                'is_available' => true,
                'is_popular' => false,
                'stock' => 100,
            ],
            [
                'name' => 'Gulai Ikan',
                'description' => 'Ikan dengan kuah gulai kuning',
                'price' => 38000.00,
                'category' => 'Ikan',
                'image' => '/assets/images/Gulai_Ikan.jpeg',
                'is_available' => true,
                'is_popular' => false,
                'stock' => 100,
            ],
            [
                'name' => 'Perkedel',
                'description' => 'Perkedel kentang goreng crispy',
                'price' => 12000.00,
                'category' => 'Gorengan',
                'image' => '/assets/images/Perkedel.jpeg',
                'is_available' => true,
                'is_popular' => false,
                'stock' => 100,
            ],
            [
                'name' => 'Telur Balado',
                'description' => 'Telur dengan sambal balado pedas',
                'price' => 15000.00,
                'category' => 'Telur',
                'image' => '/assets/images/Telur_Balado.jpeg',
                'is_available' => true,
                'is_popular' => false,
                'stock' => 100,
            ],
            [
                'name' => 'Gulai Tunjang',
                'description' => 'Kikil sapi dengan kuah gulai',
                'price' => 33000.00,
                'category' => 'Daging',
                'image' => '/assets/images/Gulai_Tunjang.jpeg',
                'is_available' => true,
                'is_popular' => false,
                'stock' => 100,
            ],
            [
                'name' => 'Udang Balado',
                'description' => 'Udang segar dengan sambal balado',
                'price' => 48000.00,
                'category' => 'Seafood',
                'image' => '/assets/images/Udang_Balado.jpeg',
                'is_available' => true,
                'is_popular' => false,
                'stock' => 100,
            ],
            [
                'name' => 'Nasi Putih',
                'description' => 'Nasi Putih Pulen',
                'price' => 10000.00,
                'category' => 'Nasi',
                'image' => '/assets/images/Nasi.jpeg',
                'is_available' => true,
                'is_popular' => false,
                'stock' => 100,
            ],
            [
                'name' => 'Es Cendol',
                'description' => 'Es Cendol Segar',
                'price' => 20000.00,
                'category' => 'Minuman',
                'image' => '/assets/images/Es_Cendol.jpeg',
                'is_available' => true,
                'is_popular' => false,
                'stock' => 100,
            ],
            [
                'name' => 'Es Teh Manis',
                'description' => 'Es Teh Manis Segar',
                'price' => 15000.00,
                'category' => 'Minuman',
                'image' => '/assets/images/Es_TehManis.jpeg',
                'is_available' => true,
                'is_popular' => false,
                'stock' => 100,
            ],
            [
                'name' => 'Es jeruk',
                'description' => 'Es Jeruk Segar',
                'price' => 20000.00,
                'category' => 'Minuman',
                'image' => '/assets/images/Es_Jeruk.jpeg',
                'is_available' => true,
                'is_popular' => false,
                'stock' => 100,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['name' => $menu['name']], // Find by name
                $menu // Update or create with these values
            );
        }
    }
}
