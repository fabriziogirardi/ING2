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
        Schema::table('retired_reservations', function (Blueprint $table) {
            $table->foreignIdFor(Reservation::class)->nullable()->unique()->after('id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retired_reservations', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(Reservation::class);
            $table->dropColumn('reservation_id');
        });
    }
};
