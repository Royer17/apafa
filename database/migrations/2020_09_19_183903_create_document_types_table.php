<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentTypesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('document_types', function (Blueprint $table) {
			$table->increments('id');
			$table->string('code')->nullable();
			$table->string('name');
			$table->string('sigla')->nullable();
			$table->boolean('status')->default(true);
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
		Schema::drop('document_types');
	}
}
