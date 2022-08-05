<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
			if (Schema::hasColumn('users', 'name')) {
				$table->dropColumn('name');
			}
			if (!Schema::hasColumn('users', 'last_name')) {
				$table->string('first_name')->after('id');
			}
			if (!Schema::hasColumn('users', 'last_name')) {
				$table->string('last_name')->after('first_name');
			}
			if (!Schema::hasColumn('users', 'phone_number')) {
				$table->string('phone_number')->after('email');
			}
			if (!Schema::hasColumn('users', 'role')) {
				$table->string('role')->after('email');;
			}

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
