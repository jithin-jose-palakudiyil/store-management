<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         
        if(DB::table('module')->get()->count() == 0){
            $tasks = 
            [
                [  'id' => 1, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Measurements',                       'slug' => 'measurements' ],
                [  'id' => 2, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Maintenance Type',                   'slug' => 'maintenance-type' ],
                [  'id' => 3, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Users',                              'slug' => 'users' ],
                [  'id' => 4, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Calibration Type',                   'slug' => 'calibration-type' ],
                [  'id' => 5, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Suppliers',                          'slug' => 'suppliers' ],
                [  'id' => 6, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Item Category',                      'slug' => 'item-category' ],
                [  'id' => 7, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Item',                               'slug' => 'item' ],
                [  'id' => 8, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Maintenance',                        'slug' => 'maintenance' ],
                [  'id' => 9, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Calibration',                        'slug' => 'calibration' ],
                [  'id' => 10, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Licence Renewal',                   'slug' => 'licence-renewal' ],
                [  'id' => 11, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Store',                             'slug' => 'store' ],
                [  'id' => 12, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Purchase Entry',                    'slug' => 'purchase-entry' ],
                [  'id' => 13, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Indents',                           'slug' => 'indents' ],
                [  'id' => 14, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Breakage',                          'slug' => 'breakage' ],
                [  'id' => 15, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Gate Pass',                         'slug' => 'gate-pass' ],
                [  'id' => 16, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Reports',                           'slug' => 'reports' ],
                [  'id' => 17, 'is_master' => 1, 'is_store' => 1, 'status' => 1, 'name' => 'Barcode',                           'slug' => 'barcode' ],
                
            ];
             
            DB::table('module')->insert($tasks);
         }
     
    }
}
