<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('admin');
        });

        // Existing linked employee accounts ko employee role do.
        DB::table('users')
            ->whereIn(
                'id',
                DB::table('employees')
                    ->whereNotNull('user_id')
                    ->select('user_id')
            )
            ->update(['role' => 'employee']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
