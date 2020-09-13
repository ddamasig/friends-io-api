<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friend_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('owner_id')
                ->references('id')
                ->on('users');
            $table->integer('user_id')
                ->references('id')
                ->on('users');
            $table->string('status')
                ->default('pending');
            $table->softDeletes();
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
        Schema::dropIfExists('friend_requests');
    }
}
