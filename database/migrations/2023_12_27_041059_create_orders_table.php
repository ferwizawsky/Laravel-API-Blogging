<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->integer('total_price');
            $table->text("message")->nullable();
            $table->enum('payment_status', ['1', '2', '3'])->comment('1=waiting, 2=paid, 3=expired');
            $table->string('snap_token', 36)->nullable();

            $table->unsignedBigInteger('user_id');
            // $table->unsignedBigInteger('component_id');


            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');  
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
