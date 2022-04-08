<?php

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsMetadataTable extends Migration
{
	public function getTable() : string {
		return with(new \Egent\Setting\Models\Metadata)->getTable();
	}
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$table = $this->getTable();
		if (Schema::hasTable($table)) {
			return;
		}
		Schema::create($table, function (Blueprint $table) {
			$table->id();

			$table->string('name')->unique();
			$table->string('type');
			$table->string('default')->nullable();
			$table->boolean('is_enabled')->default(true);

			$table->string('group')->default('default');
			$table->string('bag')->default(config('laraset.default', 'users'));

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
	{
		Schema::dropIfExists($this->getTable());
	}
}