<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('structure_headings', function (Blueprint $table) {
            $table->string('subheading')->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('structure_headings', function (Blueprint $table) {
            $table->dropColumn('subheading');
        });
    }
};

