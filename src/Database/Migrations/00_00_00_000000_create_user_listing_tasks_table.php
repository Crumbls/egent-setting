<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserListingTasksTable extends Migration
{
	public function getTable() : string {
		return with(new \Egent\Setting\Models\ListingTask())->getTable();
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
			$table->unsignedBigInteger('id')->autoIncrement();
			$table->foreignIdFor(\App\Models\User::class)->nullable()->default(null);
			// TODO: Change this to a smaller integer.  Something that fix our max entities per user per library * total libraries
			$table->foreignIdFor(\Egent\Setting\Models\ListingTaskLibrary::class)->nullable()->default(null);
			$table->unsignedBigInteger('ord');
			$table->string('title',50);
//			$table->text('message');
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
		Schema::dropIfExists($this->getTable());
	}
}