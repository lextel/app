<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApptokenTable extends Migration {

    private $table = 'apptokens';
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
                $table->integer('member_id');
                $table->string('token', 64)->index();
                $table->integer('is_delete')->default(0);
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
