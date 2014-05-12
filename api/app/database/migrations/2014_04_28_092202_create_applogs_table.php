<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApplogsTable extends Migration {
    private $table = 'applogs';
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
        //增加新列
        Schema::table($this->table, function($table)
        {
            if (!Schema::hasColumn($this->table, 'award'))
            {
                $table->integer('award');
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
        //Schema::drop($this->table);
    }

}
