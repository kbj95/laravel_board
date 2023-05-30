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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            $table->timestamp('email_verified_at')->nullable(); // 이메일 인증
            $table->rememberToken(); // 로그인 유지하기 기능 (엘로퀀트에서만 지원)
            $table->timestamps();
            $table->softDeletes(); // 엘로퀀트에서만 지원
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
