<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        if( $this->call(ModuleSeeder::class))
        $this->command->info('Table Module seeded!');
        
        if( $this->call(PermissionSeeder::class))
        $this->command->info('Table Permission seeded!');
        
        if( $this->call(UsersTableSeeder::class))
        $this->command->info('Table Users seeded!'); 
    }
}
