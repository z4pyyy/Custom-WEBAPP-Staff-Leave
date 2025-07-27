<?php

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
        Schema::table('users', function (Blueprint $table) {
        $table->enum('department', ['Homs Living', 'Lavizo', 'Bedding & Co.'])->nullable();
        $table->date('birthday')->nullable();
        $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['department', 'birthday', 'gender']);
        });
    }
};
