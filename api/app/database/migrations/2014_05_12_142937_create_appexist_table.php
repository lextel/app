<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppexistTable extends Migration {
    private $table = 'appexists';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable($this->table)){
            Schema::create($this->table, function(Blueprint $table) {
                $table->increments('id');
                $table->string('imei');
                $table->string('package');
                $table->integer('created_at')->default(0);
                $table->integer('updated_at')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
