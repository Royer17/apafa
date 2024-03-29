<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('orders', function (Blueprint $table) {
			$table->increments('id');
			$table->string('code');
			$table->unsignedInteger('document_type_id');
			$table->string('number')->nullable();
			$table->integer('folios')->default(0);
			$table->text('subject')->nullable();
			$table->text('notes')->nullable();
			$table->string('attached_file')->nullable();
			$table->integer('status');
			$table->string('year')->nullable();
			$table->unsignedInteger('entity_id');
			$table->unsignedInteger('office_id');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('orders');
	}
}
