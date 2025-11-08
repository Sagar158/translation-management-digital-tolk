<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('translations', function (Blueprint $table) {
            $table->index('locale');  // Add index to 'locale'
            $table->index('key');     // Add index to 'key'
            $table->index('tag');     // Add index to 'tag'
        });
    }

    public function down()
    {
        Schema::table('translations', function (Blueprint $table) {
            $table->dropIndex(['locale']);
            $table->dropIndex(['key']);
            $table->dropIndex(['tag']);
        });
    }

};
