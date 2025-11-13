<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Categories
        $categories = [
            [
                'name' => 'Minuman',
                'description' => 'Kategori minuman kantin',
            ],
            [
                'name' => 'Makanan Ringan',
                'description' => 'Kategori makanan ringan/snack',
            ],
            [
                'name' => 'Makanan Berat',
                'description' => 'Kategori makanan utama',
            ],
            [
                'name' => 'Peralatan',
                'description' => 'Kategori peralatan kantin',
            ],
            [
                'name' => 'Supplies',
                'description' => 'Perlengkapan kantin',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Sample Products
        $products = [
            [
                'category_id' => 1,
                'name' => 'Air Mineral 600ml',
                'sku' => 'DRINK-001',
                'description' => 'Botol air mineral 600ml',
                'price' => 5000,
                'quantity' => 50,
                'min_quantity' => 20,
                'max_quantity' => 100,
                'unit' => 'botol',
                'supplier' => 'PT Aqua Golden Mississippi',
            ],
            [
                'category_id' => 1,
                'name' => 'Teh Botol',
                'sku' => 'DRINK-002',
                'description' => 'Teh botol siap minum 350ml',
                'price' => 6000,
                'quantity' => 40,
                'min_quantity' => 15,
                'max_quantity' => 80,
                'unit' => 'botol',
                'supplier' => 'PT Sinar Sosro',
            ],
            [
                'category_id' => 2,
                'name' => 'Keripik Kentang',
                'sku' => 'SNACK-001',
                'description' => 'Keripik kentang 50g',
                'price' => 7500,
                'quantity' => 30,
                'min_quantity' => 10,
                'max_quantity' => 60,
                'unit' => 'kemasan',
                'supplier' => 'Lay\'s Indonesia',
            ],
            [
                'category_id' => 2,
                'name' => 'Coklat Bar',
                'sku' => 'SNACK-002',
                'description' => 'Coklat bar 30g',
                'price' => 10000,
                'quantity' => 25,
                'min_quantity' => 10,
                'max_quantity' => 50,
                'unit' => 'pcs',
                'supplier' => 'Nestle Indonesia',
            ],
            [
                'category_id' => 3,
                'name' => 'Nasi Kuning 1 Porsi',
                'sku' => 'FOOD-001',
                'description' => 'Nasi kuning 1 porsi',
                'price' => 15000,
                'quantity' => 20,
                'min_quantity' => 5,
                'max_quantity' => 40,
                'unit' => 'porsi',
                'supplier' => 'Katering Kantin',
            ],
            [
                'category_id' => 4,
                'name' => 'Gelas Plastik 240ml',
                'sku' => 'EQUIP-001',
                'description' => 'Gelas plastik 240ml untuk minuman',
                'price' => 1500,
                'quantity' => 200,
                'min_quantity' => 100,
                'max_quantity' => 500,
                'unit' => 'pack',
                'supplier' => 'PT Plastik Indonesia',
            ],
            [
                'category_id' => 5,
                'name' => 'Serviette/Tisu',
                'sku' => 'SUPPLY-001',
                'description' => 'Serviette/Tisu 100 lembar',
                'price' => 3000,
                'quantity' => 80,
                'min_quantity' => 30,
                'max_quantity' => 150,
                'unit' => 'pack',
                'supplier' => 'Supplier Supplies',
            ],
            [
                'category_id' => 5,
                'name' => 'Sedotan Plastik',
                'sku' => 'SUPPLY-002',
                'description' => 'Sedotan plastik per bungkus',
                'price' => 2500,
                'quantity' => 60,
                'min_quantity' => 20,
                'max_quantity' => 100,
                'unit' => 'pack',
                'supplier' => 'Supplier Supplies',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
