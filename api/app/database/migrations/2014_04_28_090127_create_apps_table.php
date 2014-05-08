<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAppsTable extends Migration {
    private $table = 'apps';
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
                $table->string('package');
                $table->string('title');
                $table->string('icon');
                $table->integer('award')->default(0);
                $table->integer('is_delete')->default(0);
                $table->integer('sort')->default(0);
                $table->integer('status')->default(0);
                $table->text('images');
                $table->text('summary');
                $table->string('link');
                $table->integer('os')->default(0);
                $table->string('size');
                $table->integer('created_at')->default(0);
                $table->integer('updated_at')->default(0);
            });
        }
        //增加新列
        Schema::table($this->table, function($table)
        {
            if (!Schema::hasColumn($this->table, 'os'))
            {
                $table->integer('os')->default(0);
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
        Schema::drop($this->table);
    }

}
