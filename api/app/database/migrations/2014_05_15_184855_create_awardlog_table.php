<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAwardlogTable extends Migration {

     private $table = 'awardlog';
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
                $table->integer('app_id');
                $table->string('package')->index();
                $table->string('title');
                $table->integer('award');
                $table->integer('status')->default(0);
                $table->integer('member_id')->default(0);
                $table->string('username')->default('');
                $table->string('imei');
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
