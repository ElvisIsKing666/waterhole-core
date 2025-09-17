<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->timestamp('hidden_at')->nullable();

            $table
                ->foreignId('hidden_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('hidden_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('hidden_at');
        });
        
        // Skip foreign key drop for SQLite as it's not supported
        if (DB::connection()->getDriverName() !== 'sqlite') {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropConstrainedForeignId('hidden_by');
            });
        }
        
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('hidden_reason');
        });
    }
};
