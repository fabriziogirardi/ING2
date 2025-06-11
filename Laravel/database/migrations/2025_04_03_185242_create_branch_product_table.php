<?php

use App\Models\Branch;
use App\Models\Product;
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
        Schema::create('branch_product', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Branch::class)->nullable()->unique()->constrained();
            $table->foreignIdFor(Product::class)->nullable()->unique()->constrained();
            $table->unsignedTinyInteger('quantity')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['branch_id', 'product_id'], 'branch_product_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_product');
    }
};
