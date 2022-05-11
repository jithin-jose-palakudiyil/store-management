<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */ 
    public function run()
    {
           if(DB::table('users')->get()->count() == 0){
             $tasks =  [
                            [
                                'name'      =>  'Store Admin',
                                'username'     =>  'master',
                                'role'    =>  'master', 
                                'is_developer'    =>  '1',
                                'status'    =>  '1', 
                                'password'  =>  bcrypt('password'),
                            ],
//                            [
//                                'name' => 'developer',
//                                'email' => 'dev@cpas.com',
//                                'status'=>'1',
//                                'type'=>'2', // this user is developer
//                                'password' => bcrypt('password'),
//                            ]
                        ];
             
             DB::table('users')->insert($tasks);
         }
    }
}
