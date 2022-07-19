<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Create Requests table
	 *
	 * @return void
	 */
	public function up()
	: void
	{
		Schema::create('request_users', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->index();
			$table->foreignId('requested_user_id')->index();
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
		Schema::dropIfExists('request_users');
	}
};
