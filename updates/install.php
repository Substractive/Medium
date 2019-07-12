<?php namespace  IdeaVerum\Car\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Install extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('ideaverum_medium_settings')){
            Schema::create('ideaverum_medium_settings',function($table){
                $table->increments('id');
                $table->string('type');
                $table->string('value');
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('ideaverum_medium_articles')){
            Schema::create('ideaverum_medium_articles',function($table){
                $table->increments('id');
                $table->integer('author_id');
                $table->string('title');
                $table->string('local_url');
                $table->string('link');
                $table->text('content');
                $table->dateTime('publish_date');
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('ideaverum_medium_authors')){
            Schema::create('ideaverum_medium_authors',function($table){
                $table->increments('id');
                $table->boolean('name');
                $table->timestamps();
            });
        }


    }

    public function down()
    {
        if(Schema::hasTable('ideaverum_medium_articles'))
            Schema::drop('ideaverum_medium_articles');
        if(Schema::hasTable('ideaverum_medium_authors'))
            Schema::drop('ideaverum_medium_authors');
        if(Schema::hasTable('ideaverum_medium_settings'))
            Schema::drop('ideaverum_medium_settings');

    }
}
