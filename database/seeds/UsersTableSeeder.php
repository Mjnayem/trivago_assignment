<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([ 'name' => 'Mj Nayem', 'email'=>'mjnayem@gmail.com', 'password'=>'$2y$10$4Nn39y4ZvvfrMNW8TitizOXiuH55TYHiYQAgM9Zr0rJ7WrbzCZ1rS']);// password is mjnayem
    }
}
