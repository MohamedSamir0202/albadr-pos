<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	public function up()
	{
		Schema::create('settings', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->string('spatie_package');
		});
	}

	public function down()
	{
		Schema::drop('settings');
	}
}
