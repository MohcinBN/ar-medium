<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Modify tables to use UUID
        Schema::table('users', function (Blueprint $table) {
            $table->string('id', 36)->change();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('id', 36)->change();
            $table->string('user_id', 36)->change();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->string('id', 36)->change();
            $table->string('user_id', 36)->change();
            $table->string('post_id', 36)->change();
        });

        Schema::table('post_tag', function (Blueprint $table) {
            $table->string('post_id', 36)->change();
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('use_uuid', function (Blueprint $table) {
            //
        });
    }
};
