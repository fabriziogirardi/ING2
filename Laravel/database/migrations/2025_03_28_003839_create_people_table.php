<?php

use App\Models\GovernmentIdType;
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
        Schema::create('people', static function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->foreignIdFor(GovernmentIdType::class)->constrained();
            $table->date('birth_date');
            $table->string('government_id_number');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['government_id_type_id', 'government_id_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
