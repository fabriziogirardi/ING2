<?php

use App\Models\ProductModel;
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
        Schema::create('products', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->json('images_json')->nullable();
            $table->foreignIdFor(ProductModel::class)->nullable()->constrained();
            $table->decimal('price', 12);
            $table->smallInteger('min_days');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['name', 'product_model_id'], 'unique_product_name_per_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
