<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'is_primary']);
        });

        $now = now();

        DB::table('products')
            ->whereNotNull('image_path')
            ->where('image_path', '<>', '')
            ->where('image_path', 'not like', 'http://%')
            ->where('image_path', 'not like', 'https://%')
            ->orderBy('id')
            ->chunkById(100, function ($products) use ($now): void {
                DB::table('product_images')->insert(
                    $products->map(fn ($product): array => [
                        'product_id' => $product->id,
                        'image_path' => ltrim($product->image_path, '/'),
                        'is_primary' => true,
                        'sort_order' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])->all()
                );
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
