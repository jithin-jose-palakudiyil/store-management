<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         if(DB::table('permissions')->get()->count() == 0){
            $tasks = 
            [
                /* Measurement*/
                [   'name'      => 'Measurement List', 'slug'        => 'measurement-list',     'module_id' =>  1, 'status'    =>  1 ],
                [   'name'      => 'Measurement Create', 'slug'      => 'measurement-create',   'module_id' =>  1, 'status'    =>  1 ],
                [   'name'      => 'Measurement Edit',   'slug'      => 'measurement-edit',     'module_id' =>  1, 'status'    =>  1 ],
                [   'name'      => 'Measurement Delete', 'slug'      => 'measurement-delete',   'module_id' =>  1, 'status'    =>  1 ],
                
                /* Maintenance Type*/
                [   'name'      => 'Maintenance  Type List',   'slug'      => 'maintenance-type-list',      'module_id' =>  2, 'status'    =>  1 ],
                [   'name'      => 'Maintenance  Type Create', 'slug'      => 'maintenance-type-create',    'module_id' =>  2, 'status'    =>  1 ],
                [   'name'      => 'Maintenance  Type Edit',   'slug'      => 'maintenance-type-edit',      'module_id' =>  2, 'status'    =>  1 ],
                [   'name'      => 'Maintenance  Type Delete', 'slug'      => 'maintenance-type-delete',    'module_id' =>  2, 'status'    =>  1 ],
                
                /* users*/
                [   'name'      => 'Users List',                'slug'      => 'users-list',                'module_id' =>  3, 'status'    =>  1 ],
                [   'name'      => 'Users Create',              'slug'      => 'users-create',              'module_id' =>  3, 'status'    =>  1 ],
                [   'name'      => 'Users Edit',                'slug'      => 'users-edit',                'module_id' =>  3, 'status'    =>  1 ],
                [   'name'      => 'Users Delete',              'slug'      => 'users-delete',              'module_id' =>  3, 'status'    =>  1 ],
                [   'name'      => 'Users Permissions Assign',  'slug'      => 'users-permissions-assign',  'module_id' =>  3, 'status'    =>  1 ],
                
                
                /* Calibration Type*/
                [   'name'      => 'Calibration  Type List',   'slug'      => 'calibration-type-list',      'module_id' =>  4, 'status'    =>  1 ],
                [   'name'      => 'Calibration  Type Create', 'slug'      => 'calibration-type-create',    'module_id' =>  4, 'status'    =>  1 ],
                [   'name'      => 'Calibration  Type Edit',   'slug'      => 'calibration-type-edit',      'module_id' =>  4, 'status'    =>  1 ],
                [   'name'      => 'Calibration  Type Delete', 'slug'      => 'calibration-type-delete',    'module_id' =>  4, 'status'    =>  1 ],
                
                /* Suppliers*/
                [   'name'      => 'Suppliers List',    'slug'      => 'suppliers-list',     'module_id' =>  5, 'status'    =>  1 ],
                [   'name'      => 'Suppliers Create',  'slug'      => 'suppliers-create',   'module_id' =>  5, 'status'    =>  1 ],
                [   'name'      => 'Suppliers Edit',    'slug'      => 'suppliers-edit',     'module_id' =>  5, 'status'    =>  1 ],
                [   'name'      => 'Suppliers Delete',  'slug'      => 'suppliers-delete',   'module_id' =>  5, 'status'    =>  1 ],
                
                /*Item Category*/
                [   'name'      => 'Item category List',    'slug'      => 'item-category-list',     'module_id' =>  6, 'status'    =>  1 ],
                [   'name'      => 'Item category Create',  'slug'      => 'item-category-create',   'module_id' =>  6, 'status'    =>  1 ],
                [   'name'      => 'Item category Edit',    'slug'      => 'item-category-edit',     'module_id' =>  6, 'status'    =>  1 ],
                [   'name'      => 'Item category Delete',  'slug'      => 'item-category-delete',   'module_id' =>  6, 'status'    =>  1 ],
                
                /*Item Category*/
                [   'name'      => 'Item List',    'slug'      => 'item-list',     'module_id' =>  7, 'status'    =>  1 ],
                [   'name'      => 'Item Create',  'slug'      => 'item-create',   'module_id' =>  7, 'status'    =>  1 ],
                [   'name'      => 'Item Edit',    'slug'      => 'item-edit',     'module_id' =>  7, 'status'    =>  1 ],
                [   'name'      => 'Item Delete',  'slug'      => 'item-delete',   'module_id' =>  7, 'status'    =>  1 ],
                [   'name'      => 'Item Batch',  'slug'      => 'item-batch',   'module_id' =>  7, 'status'    =>  1 ],
                
                
                /*Maintenance*/
                [   'name'      => 'Maintenance List',    'slug'      => 'maintenance-list',     'module_id' =>  8, 'status'    =>  1 ],
                [   'name'      => 'Maintenance Create',  'slug'      => 'maintenance-create',   'module_id' =>  8, 'status'    =>  1 ],
                [   'name'      => 'Maintenance Edit',    'slug'      => 'maintenance-edit',     'module_id' =>  8, 'status'    =>  1 ],
                [   'name'      => 'Maintenance Delete',  'slug'      => 'maintenance-delete',   'module_id' =>  8, 'status'    =>  1 ],
                [   'name'      => 'Maintenance Upload',  'slug'      => 'maintenance-upload',   'module_id' =>  8, 'status'    =>  1 ],
                
                /*Calibration*/
                [   'name'      => 'Calibration List',    'slug'      => 'calibration-list',     'module_id' =>  9, 'status'    =>  1 ],
                [   'name'      => 'Calibration Create',  'slug'      => 'calibration-create',   'module_id' =>  9, 'status'    =>  1 ],
                [   'name'      => 'Calibration Edit',    'slug'      => 'calibration-edit',     'module_id' =>  9, 'status'    =>  1 ],
                [   'name'      => 'Calibration Delete',  'slug'      => 'calibration-delete',   'module_id' =>  9, 'status'    =>  1 ],
                [   'name'      => 'Calibration Upload',  'slug'      => 'calibration-upload',   'module_id' =>  9, 'status'    =>  1 ],
                
                 /*Licence Renewal*/
                [   'name'      => 'Licence Renewal List',    'slug'      => 'licence-renewal-list',     'module_id' =>  10, 'status'    =>  1 ],
                [   'name'      => 'Licence Renewal Create',  'slug'      => 'licence-renewal-create',   'module_id' =>  10, 'status'    =>  1 ],
                [   'name'      => 'Licence Renewal Edit',    'slug'      => 'licence-renewal-edit',     'module_id' =>  10, 'status'    =>  1 ],
                [   'name'      => 'Licence Renewal Delete',  'slug'      => 'licence-renewal-delete',   'module_id' =>  10, 'status'    =>  1 ],
                [   'name'      => 'Licence Renewal Upload',  'slug'      => 'licence-renewal-upload',   'module_id' =>  10, 'status'    =>  1 ],
                
                  
                /*store*/
                [   'name'      => 'Store List',    'slug'      => 'store-list',     'module_id' =>  11, 'status'    =>  1 ],
                [   'name'      => 'Store Create',  'slug'      => 'store-create',   'module_id' =>  11, 'status'    =>  1 ],
                [   'name'      => 'Store Edit',    'slug'      => 'store-edit',     'module_id' =>  11, 'status'    =>  1 ],
                [   'name'      => 'Store Delete',  'slug'      => 'store-delete',   'module_id' =>  11, 'status'    =>  1 ],
               
                /*Purchase Entry*/
                [   'name'      => 'Purchase Entry List',    'slug'      => 'purchase-entry-list',     'module_id' =>  12, 'status'    =>  1 ],
                [   'name'      => 'Purchase Entry Create',  'slug'      => 'purchase-entry-create',   'module_id' =>  12, 'status'    =>  1 ],
                [   'name'      => 'Purchase Entry Edit',    'slug'      => 'purchase-entry-edit',     'module_id' =>  12, 'status'    =>  1 ],
                [   'name'      => 'Purchase Entry Delete',  'slug'      => 'purchase-entry-delete',   'module_id' =>  12, 'status'    =>  1 ],
                [   'name'      => 'Purchase Entry view',    'slug'      => 'purchase-entry-view',     'module_id' =>  12, 'status'    =>  1 ],
               
                /*Indent*/
                [   'name'      => 'Indent List',    'slug'      => 'indent-list',     'module_id' =>  13, 'status'    =>  1 ],
                [   'name'      => 'Indent Create',  'slug'      => 'indent-create',   'module_id' =>  13, 'status'    =>  1 ],
                [   'name'      => 'Indent Edit',    'slug'      => 'indent-edit',     'module_id' =>  13, 'status'    =>  1 ],
                [   'name'      => 'Indent Transfer',    'slug'  => 'indent-transfer', 'module_id' =>  13, 'status'    =>  1 ],
//                [   'name'      => 'Indent Delete',  'slug'      => 'indent-delete',   'module_id' =>  13, 'status'    =>  1 ],
                
                /*Breakage*/
                [   'name'      => 'Breakage List',    'slug'      => 'breakage-list',     'module_id' =>  14, 'status'    =>  1 ],
                [   'name'      => 'Breakage Create',  'slug'      => 'breakage-create',   'module_id' =>  14, 'status'    =>  1 ],
                [   'name'      => 'Breakage Edit',    'slug'      => 'breakage-edit',     'module_id' =>  14, 'status'    =>  1 ],
                [   'name'      => 'Breakage Delete',  'slug'      => 'breakage-delete',   'module_id' =>  14, 'status'    =>  1 ],
                [   'name'      => 'Breakage Authority Approval',  'slug'      => 'breakage-authority-approval',   'module_id' =>  14, 'status'    =>  1 ],
                
                /*Gate Pass*/
                [   'name'      => 'Gate Pass List',    'slug'      => 'gate-pass-list',     'module_id' =>  15, 'status'    =>  1 ],
                [   'name'      => 'Gate Pass Create',  'slug'      => 'gate-pass-create',   'module_id' =>  15, 'status'    =>  1 ],
                [   'name'      => 'Gate Pass Edit',    'slug'      => 'gate-pass-edit',     'module_id' =>  15, 'status'    =>  1 ],
                [   'name'      => 'Gate Pass Delete',  'slug'      => 'gate-pass-delete',   'module_id' =>  15, 'status'    =>  1 ],
                
                /* Reports*/
                [   'name'      => 'Report List', 'slug'        => 'report-list',     'module_id' =>  16, 'status'    =>  1 ],
                
                /* Barcode*/
                [   'name'      => 'Barcode Read', 'slug'        => 'barcode-read',     'module_id' =>  17, 'status'    =>  1 ],
                
            ];
             
            DB::table('permissions')->insert($tasks);
         }
    }
}
