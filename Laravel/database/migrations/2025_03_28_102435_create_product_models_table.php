<?php

use App\Models\ProductBrand;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_models', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ProductBrand::class)->nullable()->constrained();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['name', 'product_brand_id'], 'unique_product_model_name_per_brand');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_models');
    }
};
