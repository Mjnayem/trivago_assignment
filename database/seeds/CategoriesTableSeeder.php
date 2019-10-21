<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([ 'name' => 'hotel']);
        DB::table('categories')->insert([ 'name' => 'alternative']);
        DB::table('categories')->insert([ 'name' => 'hostel']);
        DB::table('categories')->insert( [ 'name' => 'lodge']);
        DB::table('categories')->insert([ 'name' => 'resort']);
        DB::table('categories')->insert([ 'name' => 'guest-house']);

    }
}
