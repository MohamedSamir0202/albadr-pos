<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	public function up()
	{
		Schema::create('clients', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name');
			$table->string('email')->unique();
			$table->string('phone')->unique();
			$table->string('address')->nullable();
			$table->decimal('balance', 12,2)->default(0);
			$table->tinyInteger('status')->default(\App\Enums\ClientStatusEnum::active);
            $table->tinyInteger('registered_via')->default(\App\Enums\ClientRegistrationEnum::pos);
		});
	}

	public function down()
	{
		Schema::drop('clients');
	}
}
