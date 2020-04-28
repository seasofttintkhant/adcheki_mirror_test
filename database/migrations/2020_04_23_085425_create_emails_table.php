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
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->string('email')->index();
            $table->boolean('is_valid')->nullable();
            $table->unsignedInteger('status')
                ->nullable()
                ->comment('0 => not exist, 1 => unknown, 2 => exist');
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
