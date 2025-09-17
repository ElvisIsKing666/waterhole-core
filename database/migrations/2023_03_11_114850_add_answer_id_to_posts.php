<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table
                ->foreignId('answer_id')
                ->nullable()
                ->constrained('comments')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Skip foreign key drop for SQLite as it's not supported
        if (DB::connection()->getDriverName() !== 'sqlite') {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropConstrainedForeignId('answer_id');
            });
        } else {
            // For SQLite, just drop the column without the foreign key constraint
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('answer_id');
            });
        }
    }
};
