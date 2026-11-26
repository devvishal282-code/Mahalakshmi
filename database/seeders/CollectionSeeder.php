<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            ['url' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=700&q=85', 'alt' => 'Elegant Indian outfit in a warm festive tone'],
            ['url' => 'https://images.unsplash.com/photo-1585488434455-71d9f9322d7c?auto=format&fit=crop&w=700&q=85', 'alt' => 'Detailed Indian outfit in a rich jewel tone'],
            ['url' => 'https://images.unsplash.com/photo-1614252369475-531eba835eb1?auto=format&fit=crop&w=700&q=85', 'alt' => 'Contemporary Indian outfit with a graceful silhouette'],
            ['url' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=700&q=85', 'alt' => 'Printed Indian outfit for a special occasion'],
        ];

        $collections = [
            ['slug' => 'festive-favorites', 'title' => 'Festive Favourites', 'description' => 'Celebrate the season with occasion-ready styles, thoughtful details, and festive colour.', 'route_name' => 'collections.festive-favorites'],
            ['slug' => 'coord-sets', 'title' => 'Co-ord Sets', 'description' => 'Effortless matching sets for celebrations, get-togethers, and every stylish day in between.', 'route_name' => 'collections.coord-sets'],
            ['slug' => 'designer-kurtas', 'title' => 'Designer Kurtas', 'description' => 'Discover expressive prints, considered details, and versatile kurta styles to make your own.', 'route_name' => 'collections.designer-kurtas'],
            ['slug' => 'wedding-edit', 'title' => 'Wedding Edit', 'description' => 'Find an occasion-ready look for the celebrations, the ceremonies, and the moments in between.', 'route_name' => 'collections.wedding-edit'],
            ['slug' => 'autumn-edit', 'title' => 'The Autumn Edit', 'description' => 'Warm hues and graceful silhouettes bring a little extra character to your seasonal wardrobe.', 'route_name' => 'collections.autumn-edit'],
            ['slug' => 'everyday-elegance', 'title' => 'Everyday Elegance', 'description' => 'Easy-to-wear favourites with thoughtful details, made for days that deserve a little polish.', 'route_name' => 'collections.everyday-elegance'],
            ['slug' => 'pastel-stories', 'title' => 'Pastel Stories', 'description' => 'A soft palette of delicate colours and graceful shapes for your next memorable look.', 'route_name' => 'collections.pastel-stories'],
            ['slug' => 'just-added', 'title' => 'Just Added', 'description' => 'Meet the latest additions to the Mahalakshmi collection, selected for your next special moment.', 'route_name' => 'collections.just-added'],
            ['slug' => 'ethnic-wear', 'title' => 'Ethnic Wear', 'description' => 'Explore Indian-inspired styles made to bring colour, craft, and confidence to your wardrobe.', 'route_name' => 'collections.ethnic-wear'],
            ['slug' => 'cord-set', 'title' => 'Cord Set', 'description' => 'Discover coordinated looks that make getting dressed for any occasion feel effortless.', 'route_name' => 'collections.cord-set'],
            ['slug' => 'gown', 'title' => 'Gown', 'description' => 'Explore graceful statement silhouettes for celebrations and evenings worth remembering.', 'route_name' => 'collections.gown'],
            ['slug' => 'indo-western', 'title' => 'Indo Western', 'description' => 'A fresh mix of contemporary styling and Indian-inspired details for your occasion wardrobe.', 'route_name' => 'collections.indo-western'],
            ['slug' => 'fusion-wear', 'title' => 'Fusion Wear', 'description' => 'Find distinctive looks that bring together easy modern shapes and expressive tradition.', 'route_name' => 'collections.fusion-wear'],
        ];

        foreach ($collections as $index => $collectionData) {
            $image = $images[$index % count($images)];

            Collection::query()->firstOrCreate(
                ['slug' => $collectionData['slug']],
                array_merge($collectionData, [
                    'image_path' => $image['url'],
                    'image_alt' => $image['alt'],
                    'status' => 'active',
                    'sort_order' => $index + 1,
                ])
            );
        }
    }
}
