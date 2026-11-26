<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            User::factory()->raw([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ])
        );

        $this->call(CollectionSeeder::class);

        $categories = [];

        foreach (['Co-ord Sets', 'Festive Wear', 'Wedding Edit', 'Dupattas'] as $name) {
            $categories[$name] = Category::query()->firstOrCreate(
                ['name' => $name],
                ['status' => 'active']
            );
        }

        $products = [
            [
                'category' => 'Co-ord Sets',
                'name' => 'Moonlit Rose Co-ord Set',
                'description' => 'A soft floral co-ord set for celebrations and evenings out.',
                'price' => 1899,
                'stock_quantity' => 12,
                'image_path' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=700&q=85',
            ],
            [
                'category' => 'Co-ord Sets',
                'name' => 'Ivory Bloom Co-ord Set',
                'description' => 'An easy ivory set with delicate botanical detailing.',
                'price' => 1799,
                'stock_quantity' => 9,
                'image_path' => 'https://images.unsplash.com/photo-1581044777550-4cfa60707c03?auto=format&fit=crop&w=700&q=85',
            ],
            [
                'category' => 'Festive Wear',
                'name' => 'Lavender Festive Suit',
                'description' => 'A graceful festive suit in a rich lavender shade.',
                'price' => 2499,
                'stock_quantity' => 8,
                'image_path' => 'https://images.unsplash.com/photo-1585488434455-71d9f9322d7c?auto=format&fit=crop&w=700&q=85',
            ],
            [
                'category' => 'Festive Wear',
                'name' => 'Marigold Celebration Kurta',
                'description' => 'A bright embroidered kurta made for joyful gatherings.',
                'price' => 2199,
                'stock_quantity' => 10,
                'image_path' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=700&q=85',
            ],
            [
                'category' => 'Wedding Edit',
                'name' => 'Rosewood Wedding Ensemble',
                'description' => 'A statement ensemble with a refined occasion-ready finish.',
                'price' => 4599,
                'stock_quantity' => 5,
                'image_path' => 'https://images.unsplash.com/photo-1614252369475-531eba835eb1?auto=format&fit=crop&w=700&q=85',
            ],
            [
                'category' => 'Wedding Edit',
                'name' => 'Sage Ceremony Anarkali',
                'description' => 'A flowing anarkali in a calm sage palette.',
                'price' => 3899,
                'stock_quantity' => 6,
                'image_path' => 'https://images.unsplash.com/photo-1610189020382-668a224d9c1e?auto=format&fit=crop&w=700&q=85',
            ],
            [
                'category' => 'Dupattas',
                'name' => 'Emerald Embroidered Dupatta',
                'description' => 'An embroidered emerald accent for festive styling.',
                'price' => 899,
                'stock_quantity' => 15,
                'image_path' => 'https://images.unsplash.com/photo-1618244972963-dbee1a7edc95?auto=format&fit=crop&w=700&q=85',
            ],
            [
                'category' => 'Dupattas',
                'name' => 'Ivory Silk Dupatta',
                'description' => 'A versatile ivory dupatta with a soft, elegant drape.',
                'price' => 799,
                'stock_quantity' => 14,
                'image_path' => 'https://images.unsplash.com/photo-1605763240000-7e93b172d754?auto=format&fit=crop&w=700&q=85',
            ],
        ];

        foreach ($products as $productData) {
            $category = $categories[$productData['category']];
            unset($productData['category']);

            Product::query()->firstOrCreate(
                ['name' => $productData['name']],
                array_merge([
                    'category_id' => $category->id,
                    'status' => 'active',
                    'show_marketplace' => true,
                ], $productData)
            );
        }
    }
}
