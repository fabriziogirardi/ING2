<?php

use App\Models\Reservation;
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
        Schema::table('returned_reservations', function (Blueprint $table) {
            $table->foreignIdFor(Reservation::class)->nullable()->unique()->after('id')->constrained();
            $table->tinyInteger('rating')->unsigned()->after('reservation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('returned_reservations', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(Reservation::class);
            $table->dropColumn('reservation_id');
            $table->dropColumn('rating');
        });
    }
};
