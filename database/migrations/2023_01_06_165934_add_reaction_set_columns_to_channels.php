<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table
                ->foreignId('posts_reaction_set_id')
                ->nullable()
                ->constrained('reaction_sets')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table
                ->foreignId('comments_reaction_set_id')
                ->nullable()
                ->constrained('reaction_sets')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down()
    {
        // Skip foreign key drops for SQLite as they're not supported
        if (DB::connection()->getDriverName() !== 'sqlite') {
            Schema::table('channels', function (Blueprint $table) {
                $table->dropConstrainedForeignId('posts_reaction_set_id');
            });
            Schema::table('channels', function (Blueprint $table) {
                $table->dropConstrainedForeignId('comments_reaction_set_id');
            });
        } else {
            // For SQLite, just drop the columns without foreign key constraints
            Schema::table('channels', function (Blueprint $table) {
                $table->dropColumn('posts_reaction_set_id');
            });
            Schema::table('channels', function (Blueprint $table) {
                $table->dropColumn('comments_reaction_set_id');
            });
        }
    }
};
