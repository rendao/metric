<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiTokenToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('api_token', 80)->after('password')
                                ->unique()
                                ->nullable()
                                ->default(null);
            $table->dateTime('last_login_at')->before('created_at')
            ->nullable()
            ->default(null);

            $table->string('last_login_ip')->before('created_at')
            ->nullable()
            ->default(null);
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
            $table->dropColumn('api_token', 'last_login_at', 'last_login_ip');
        });
    }
}
