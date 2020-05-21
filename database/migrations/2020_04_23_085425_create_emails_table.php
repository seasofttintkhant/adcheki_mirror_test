<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mail_address_id')->nullable();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('email');
            $table->boolean('is_valid')->nullable();
            $table->unsignedInteger('status')
                ->nullable()
                ->comment('0 => unknown, 1 => not exist, 2 => exist');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emails');
    }
}
