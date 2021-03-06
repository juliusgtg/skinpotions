<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createTableCategories();
        $this->createTableItems();
        $this->createTableTransactions();

        $this->populateTableCategories();
        $this->populateTableItems();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('skpt_categories');
        Schema::drop('skpt_items');
        Schema::drop('skpt_transactions');
    }

    /**
     *  Naming conventions:
     *      Table: Noun in plural form.
     *      Table columns: Noun in singular form.
     * 
     *  The naming of reference columns follows this format:
     *  
     *      [table name in singular form]_[column name]
     *
     *  For example, if table Items references the column `id` of 
     *      table Categories, the column name in Items would be:
     * 
     *      category_id
     */

    private function createTableCategories()
    {
        /**
         *  CREATE TABLE IF NOT EXISTS `skpt_categories` (
         *      id int UNSIGNED NOT NULL AUTO_INCREMENT,
         *      category_name text,
         *      description text,
         *      created_at datetime,
         *      updated_at datetime,
         *      PRIMARY KEY id,
         *      UNIQUE KEY skpt_categories_category_name_unique
         *  ) ENGINE=MyISAM;
         */
        Schema::create('skpt_categories', function (Blueprint $table) {

            $table->engine = 'MyISAM';

            $table->increments('id');
            
            $table->string('category_name', 128)->unique();
            $table->mediumText('description');

            // Creates created_at and updated_at
            $table->timestamps();
        });
    }

    private function createTableItems()
    {
        /**
         *  CREATE TABLE IF NOT EXISTS `skpt_items` (
         *      id int UNSIGNED NOT NULL AUTO_INCREMENT,
         *      code varchar(128),
         *      name varchar(128),
         *      description mediumtext,
         *      unit_price float(6, 2) DEFAULT 0.0,
         *      stock int DEFAULT 0,
         *      category_id int unsigned,
         *      created_at datetime,
         *      updated_at datetime,
         *      PRIMARY KEY id
         *  ) ENGINE=MyISAM;
         */

        Schema::create('skpt_items', function (Blueprint $table) {

            $table->engine = 'MyISAM';

            $table->increments('id');

            $table->string('code', 128)->unique();
            $table->string('name', 128)->unique();
            $table->mediumText('description');
            $table->decimal('price', 6, 2)->default(0.0);
            $table->integer('quantity')->unsigned()->default(0);
            $table->integer('category_id')->unsigned()->nullable();

            $table->timestamps();
        });
    }

    private function createTableTransactions()
    {
        if(Schema::exists('skpt_transactions')) {
            Schema::drop('skpt_transactions');
        }
        else
        {
            Schema::create('skpt_transactions', function(Blueprint $table) {
                $table->engine = 'MyISAM';

                $table->increments('id');

                $table->string('item_code', 128)->index();
                $table->integer('quantity')->unsigned();
                $table->decimal('unit_price', 6, 2)->index();
                $table->decimal('order_total', 7, 2)->index();

                $table->string('reference_id')->unique();
                $table->enum('status', ['PENDING', 'PROCESSING', 'PAID', 'CANCELLED', 'RETURNED']);

                $table->timestamps();
            });
        }
    }

    private function populateTableCategories()
    {

    }

    private function populateTableItems()
    {

    }

}
