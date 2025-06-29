<?php

use App\Models\Product;
use App\Models\Wishlist;
use App\Models\WishlistSublist;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wishlist_products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Wishlist::class)
                ->constrained('wishlists')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignIdFor(Product::class)
                ->constrained('products')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlist_products');
    }
};
