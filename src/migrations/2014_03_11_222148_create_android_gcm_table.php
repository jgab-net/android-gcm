<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAndroidGcmTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('android_gcm', function(Blueprint $table) {
            $table->increments('id');
            $table->string('registration_id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::table('android_gcm', function(Blueprint $table)
        {
            $table->drop();
            $table->dropForeign('android_gcm_user_id_foreign');
        });
	}

}
