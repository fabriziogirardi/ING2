<?php

use App\Models\Wishlist;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wishlist_sublists', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Wishlist::class)
                ->constrained('wishlists')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlist_sublists');
    }
};
