<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 기존 테이블 alter
        // php artisan make:migration alter_boards_table
        // 패키지 관리자 설치 : composer require doctrine/dbal 설치후 php artisan migrate 커맨드로 실행

        Schema::table('boards', function (Blueprint $table) {
            $table->integer('hits')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
