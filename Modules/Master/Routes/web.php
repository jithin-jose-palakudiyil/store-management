<?php

/*
|--------------------------------------------------------------------------
| Constants variables
|--------------------------------------------------------------------------
|
| Here is where you can register Constants variables for your application. These
| variables are loaded by the application. Now create something great!
|
*/

define("master_prefix", "master");
define("master_guard", "master");
define("diff_in_days", "7");
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'AuthController@index');
Route::get('/activity_log', 'AuthController@activity_log');

Route::group([ 'middleware' => 'preventBackHistory','prefix' => master_prefix], function()
{   

    Route::get('/', 'AuthController@index')->name('master_index');
    Route::any('/login', 'AuthController@LoginAction')->name('master_login');
    
    /* logged user opertaions */
    Route::group(['middleware' =>  'master_auth:'.master_guard], function()
    {
        Route::get('/dashboard', 'DashboardController@index')->name('master_dashboard');
        Route::get('/logout', 'AuthController@logout')->name('master_logout');
        Route::any('activity-log', 'Profile\ActivityLogController@index' )->name('master_activity_log'); 
              
      
        
        /*
        |--------------------------------------------------------------------------
        | Web Routes for stock menu
        |--------------------------------------------------------------------------
        */ 
        Route::name('stock.')->group( function()
        {
            /*
            |--------------------------------------------------------------------------
            | Web Routes for Item Category in stock menu
            |--------------------------------------------------------------------------
            */
            Route::any('item-category-list', 'Stock\ItemCategoryController@GetDataTableList' )->name('item_category_list'); 
            Route::bind('item-category', function ($value, $route) {return Modules\Master\Entities\ItemCategory::find($value); }); 
            Route::resource( '/item-category', 'Stock\ItemCategoryController',[ 
                'names' =>  [   
                                'index'     => 'item-category',          'create'    => 'item-category.create',
                                'store'     => 'item-category.store',    'edit'      => 'item-category.edit',
                                'update'    => 'item-category.update',   'destroy'   => 'item-category.destroy' 
                            ] 
                ]
            ); 
            
           Route::get('/barcode', 'Barcode\BarcodeController@index')->name('barcode_view');;
           Route::any('/barcode-read', 'Barcode\BarcodeController@create')->name('barcode_create');;
           Route::any('/barcode-read-action/{type}/{item_id}/{uid}', 'Barcode\BarcodeController@barcode_action')->name('barcode_read_action');;
           
            /*
            |--------------------------------------------------------------------------
            | Web Routes for Item in stock menu
            |--------------------------------------------------------------------------
            */
            Route::get('items/get-batch-model/{id}', 'Stock\ItemsController@getBatchModel' ); 
            
            Route::get('items/get-delete-model/{id}', 'Stock\ItemsController@getDeleteModel' ); 
            Route::get('items/get-uid-model/{id}', 'Stock\ItemsController@getUidModel' ); 
            Route::get('items/get-barcode-model/{id}', 'Stock\ItemsController@getBarcodeModel' ); 
            
            
            Route::get('items/get-measurements-with-item-category/{id}', 'Stock\ItemsController@getMeasurementsWithItemCategory' ); 
            Route::get('items/get-usage-model/{id}', 'Stock\ItemsController@getUsageModel' ); 
            Route::post('items/store-usage', 'Stock\ItemsController@storeUsageModel' ); 
            
             
            Route::any('items-list', 'Stock\ItemsController@GetDataTableList' )->name('items_list'); 
            Route::bind('items', function ($value, $route) {return Modules\Master\Entities\ItemCategory::find($value); }); 
            Route::resource( '/items', 'Stock\ItemsController',[ 
                'names' =>  [   
                                'index'     => 'items',          'create'    => 'items.create',
                                'store'     => 'items.store',    'edit'      => 'items.edit',
                                'update'    => 'items.update',   'destroy'   => 'items.destroy' 
                            ] 
                ]
            );
            
            
             /*
            |--------------------------------------------------------------------------
            | Web Routes for Store in stock menu
            |--------------------------------------------------------------------------
            */
            Route::any('store-list', 'Stock\StoreController@GetDataTableList' )->name('store_list'); 
            Route::bind('store', function ($value, $route) {return Modules\Master\Entities\Store::find($value); }); 
            Route::resource( '/store', 'Stock\StoreController',[ 
                'names' =>  [   
                                'index'     => 'store',          'create'    => 'store.create',
                                'store'     => 'store.store',    'edit'      => 'store.edit',
                                'update'    => 'store.update',   'destroy'   => 'store.destroy' 
                            ] 
                ]
            ); 
           
            
            /*
            |--------------------------------------------------------------------------
            | Web Routes for Item Category in stock menu
            |--------------------------------------------------------------------------
            */
            Route::any('purchase-entry-autocomplete', 'Stock\PurchaseEntryController@ItemAutocomplete' ); 
            Route::get('purchase-entry/add-new-item-model', 'Stock\PurchaseEntryController@ItemModel' ); 
            Route::post('purchase-entry/store-item', 'Stock\PurchaseEntryController@StoreItem' ); 
            Route::any('purchase-entry-next/{purchase_entry_id}', 'Stock\PurchaseEntryController@EntryNext' )->name('EntryNext'); 
            
            
            Route::any('purchase-entry-list', 'Stock\PurchaseEntryController@GetDataTableList' )->name('purchase_entry_list'); 
            Route::bind('purchase-entry', function ($value, $route) {return Modules\Master\Entities\PurchaseEntry::find($value); }); 
            Route::resource( '/purchase-entry', 'Stock\PurchaseEntryController',[ 
                'names' =>  [   
                                'index'     => 'purchase-entry',          'create'    => 'purchase-entry.create',
                                'store'     => 'purchase-entry.store',    'edit'      => 'purchase-entry.edit',
                                'update'    => 'purchase-entry.update',   'destroy'   => 'purchase-entry.destroy',
                    'show'      => 'purchase-entry.show',
                            ] 
                ]
            );
            
            
            
            /*
            |--------------------------------------------------------------------------
            | Web Routes for Indents in stock menu
            |--------------------------------------------------------------------------
            */
            Route::any('indents/{Indent}/transfer', 'Stock\IndentsController@transfer' )->name('indents_transfer'); 
            Route::any('indents/{Indent}/transfer-action', 'Stock\IndentsController@transfer_action' )->name('indents_transfer_action'); 
            
            Route::any('indents/{Indent}/store-edit-action', 'Stock\IndentsController@store_action' )->name('indents_store_action'); 
            Route::any('indents/{Indent}/store-transfer-action', 'Stock\IndentsController@store_transfer_action' )->name('indents_store_transfer_action'); 
            
            Route::any('indent-autocomplete', 'Stock\IndentsController@ItemAutocomplete' ); 
            Route::any('indents-list', 'Stock\IndentsController@GetDataTableList' )->name('indents_list'); 
            Route::any('indents/store_update/{Indent}', 'Stock\IndentsController@store_update' )->name('store_update'); 
            Route::any('indents/store_from_update/{Indent}', 'Stock\IndentsController@store_from_update' )->name('store_from_update'); 
            Route::bind('indents', function ($value, $route) {return Modules\Master\Entities\Indents::find($value); }); 
            Route::resource( '/indents', 'Stock\IndentsController',[ 
                'names' =>  [   
                                'index'     => 'indents',           'create'    => 'indents.create',
                                'store'     => 'indents.store',     'show'     => 'indents.show',    
                                'edit'      => 'indents.edit',      'update'    => 'indents.update',   'destroy'   => 'indents.destroy',
                                'store_update'=>'indents.store_update'
                            ] 
                ]
            ); 
            
            
        });
        
        
        
        
        
        
        
        
        /*
        |--------------------------------------------------------------------------
        | Web Routes for extras menu
        |--------------------------------------------------------------------------
        */ 
        Route::name('extras.')->group( function()
        {
            /*
            |--------------------------------------------------------------------------
            | Web Routes for Measurements in extras menu
            |--------------------------------------------------------------------------
            */
            Route::any('measurements-list', 'Extras\MeasurementsController@GetDataTableList' )->name('measurements_list'); 
            Route::bind('measurements', function ($value, $route) {return Modules\Master\Entities\Measurements::find($value); }); 
            Route::resource( '/measurements', 'Extras\MeasurementsController',[ 
                'names' =>  [   
                                'index'     => 'measurements',          'create'    => 'measurements.create',
                                'store'     => 'measurements.store',    'edit'      => 'measurements.edit',
                                'update'    => 'measurements.update',   'destroy'   => 'measurements.destroy' 
                            ] 
                ]
            ); 
            
            /*
            |--------------------------------------------------------------------------
            | Web Routes for Maintenance Type in extras menu
            |--------------------------------------------------------------------------
            */
            Route::any('maintenance-type-list', 'Extras\MaintenanceTypeController@GetDataTableList' )->name('maintenance_type_list'); 
            Route::bind('maintenance-type', function ($value, $route) {return Modules\Master\Entities\MaintenanceType::find($value); }); 
            Route::resource( '/maintenance-type', 'Extras\MaintenanceTypeController',[ 
                'names' =>  [   
                                'index'     => 'maintenance-type',          'create'    => 'maintenance-type.create',
                                'store'     => 'maintenance-type.store',    'edit'      => 'maintenance-type.edit',
                                'update'    => 'maintenance-type.update',   'destroy'   => 'maintenance-type.destroy' 
                            ] 
                ]
            ); 
            
            /*
            |--------------------------------------------------------------------------
            | Web Routes for Module in extras menu
            |--------------------------------------------------------------------------
            */
            Route::any('module-list', 'Extras\ModuleController@GetDataTableList' )->name('module_list'); 
            Route::bind('module', function ($value, $route) {return Modules\Master\Entities\Module::find($value); }); 
            Route::resource( '/module', 'Extras\ModuleController',[ 
                'names' =>  [   
                                'index'     => 'module',          'create'    => 'module.create',
                                'store'     => 'module.store',    'edit'      => 'module.edit',
                                'update'    => 'module.update',   'destroy'   => 'module.destroy' 
                            ] 
                ]
            ); 
            
            
            /*
            |--------------------------------------------------------------------------
            | Web Routes for Permissions in extras menu
            |--------------------------------------------------------------------------
            */
           
            Route::any('permissions-list', 'Extras\PermissionController@GetDataTableList' )->name('permission_list'); 
            Route::bind('permissions', function ($value, $route) {return Modules\Master\Entities\Permission::find($value); }); 
            Route::resource( '/permissions', 'Extras\PermissionController',[ 
                'names' =>  [   
                                'index'     => 'permissions',          'create'    => 'permissions.create',
                                'store'     => 'permissions.store',    'edit'      => 'permissions.edit',
                                'update'    => 'permissions.update',   'destroy'   => 'permissions.destroy' 
                            ] 
                ]
            );
           
            
            /*
            |--------------------------------------------------------------------------
            | Web Routes for Users in extras menu
            |--------------------------------------------------------------------------
            */
            Route::get('users/get-store/{role}', 'Extras\UsersController@get_store' ); 
            Route::any('users/assigning-module-permissions/{id}', 'Extras\UsersController@module_permissions_index' ); 
            Route::any('users/save-module-permissions/{id}', 'Extras\UsersController@module_permissions_save' )->name('module_permissions_save'); 
            Route::any('users-list', 'Extras\UsersController@GetDataTableList' )->name('users_list'); 
            Route::bind('users', function ($value, $route) {return Modules\Master\Entities\Auth::find($value); }); 
            Route::resource( '/users', 'Extras\UsersController',[ 
                'names' =>  [   
                                'index'     => 'users',          'create'    => 'users.create',
                                'store'     => 'users.store',    'edit'      => 'users.edit',
                                'update'    => 'users.update',   'destroy'   => 'users.destroy' 
                            ] 
                ]
            ); 
            
            
             /*
            |--------------------------------------------------------------------------
            | Web Routes for Calibration Type in extras menu
            |--------------------------------------------------------------------------
            */
            Route::any('calibration-type-list', 'Extras\CalibrationTypeController@GetDataTableList' )->name('calibration_type_list'); 
            Route::bind('calibration-type', function ($value, $route) {return Modules\Master\Entities\CalibrationType::find($value); }); 
            Route::resource( '/calibration-type', 'Extras\CalibrationTypeController',[ 
                'names' =>  [   
                                'index'     => 'calibration-type',          'create'    => 'calibration-type.create',
                                'store'     => 'calibration-type.store',    'edit'      => 'calibration-type.edit',
                                'update'    => 'calibration-type.update',   'destroy'   => 'calibration-type.destroy' 
                            ] 
                ]
            );
            
            
            
             /*
            |--------------------------------------------------------------------------
            | Web Routes for Suppliers in extras menu
            |--------------------------------------------------------------------------
            */
            Route::any('suppliers-list', 'Extras\SuppliersController@GetDataTableList' )->name('suppliers_list'); 
            Route::bind('suppliers', function ($value, $route) {return Modules\Master\Entities\Suppliers::find($value); }); 
            Route::resource( '/suppliers', 'Extras\SuppliersController',[ 
                'names' =>  [   
                                'index'     => 'suppliers',          'create'    => 'suppliers.create',
                                'store'     => 'suppliers.store',    'edit'      => 'suppliers.edit',
                                'update'    => 'suppliers.update',   'destroy'   => 'suppliers.destroy' 
                            ] 
                ]
            ); 
            
            
            
            
            
        });
        
        
        
        
        
        
        
        
        
        /*
        |--------------------------------------------------------------------------
        | Web Routes for others menu
        |--------------------------------------------------------------------------
        */ 
        Route::name('others.')->group( function()
        {
            /*
            |--------------------------------------------------------------------------
            | Web Routes for Maintenance in Others menu
            |--------------------------------------------------------------------------
            */
            Route::any('maintenance-autocomplete', 'Others\MaintenanceController@ItemAutocomplete' ); 
            Route::any('maintenance-autocomplete-suppliers', 'Others\MaintenanceController@ItemSuppliersAutocomplete' ); 
            Route::get('maintenance/get-maintenance-days/{id}', 'Others\MaintenanceController@getMaintenanceDays' ); 
            Route::get('maintenance/get-update-model/{id}', 'Others\MaintenanceController@getUpdateModel' ); 
            Route::post('maintenance/maintenance-status-update', 'Others\MaintenanceController@statusUpdate' ); 
            
            Route::any('maintenance-list', 'Others\MaintenanceController@GetDataTableList' )->name('maintenance_list'); 
            Route::bind('maintenance', function ($value, $route) {return Modules\Master\Entities\Maintenance::find($value); }); 
            Route::resource( '/maintenance', 'Others\MaintenanceController',[ 
                'names' =>  [   
                                'index'     => 'maintenance',          'create'    => 'maintenance.create',
                                'store'     => 'maintenance.store',    'edit'      => 'maintenance.edit',
                                'update'    => 'maintenance.update',   'destroy'   => 'maintenance.destroy' 
                            ] 
                ]
            ); 
           
            
          
            
            /*
            |--------------------------------------------------------------------------
            | Web Routes for Calibration in Others menu
            |--------------------------------------------------------------------------
            */
            Route::any('calibration-autocomplete', 'Others\CalibrationController@ItemAutocomplete' ); 
            Route::get('calibration/get-calibration-days/{id}', 'Others\CalibrationController@getCalibrationDays' ); 
            Route::get('calibration/get-update-model/{id}', 'Others\CalibrationController@getUpdateModel' ); 
            Route::post('calibration/calibration-status-update', 'Others\CalibrationController@statusUpdate' ); 
            
            Route::any('calibration-list', 'Others\CalibrationController@GetDataTableList' )->name('calibration_list'); 
            Route::bind('calibration', function ($value, $route) {return Modules\Master\Entities\Calibration::find($value); }); 
            Route::resource( '/calibration', 'Others\CalibrationController',[ 
                'names' =>  [   
                                'index'     => 'calibration',          'create'    => 'calibration.create',
                                'store'     => 'calibration.store',    'edit'      => 'calibration.edit',
                                'update'    => 'calibration.update',   'destroy'   => 'calibration.destroy' 
                            ] 
                ]
            ); 
            
            
            
            /*
            |--------------------------------------------------------------------------
            | Web Routes for Maintenance in Others menu
            |--------------------------------------------------------------------------
            */
            Route::any('licence-renewal-autocomplete', 'Others\LicenceRenewalController@ItemAutocomplete' ); 
            Route::get('licence-renewal/get-update-model/{id}', 'Others\LicenceRenewalController@getUpdateModel' ); 
            Route::post('licence-renewal/licence-renewal-status-update', 'Others\LicenceRenewalController@statusUpdate' ); 
            
            Route::any('licence-renewal-list', 'Others\LicenceRenewalController@GetDataTableList' )->name('licence_renewal_list'); 
            Route::bind('licence-renewal', function ($value, $route) {return Modules\Master\Entities\LicenceRenewal::find($value); }); 
            Route::resource( '/licence-renewal', 'Others\LicenceRenewalController',[ 
                'names' =>  [   
                                'index'     => 'licence-renewal',          'create'    => 'licence-renewal.create',
                                'store'     => 'licence-renewal.store',    'edit'      => 'licence-renewal.edit',
                                'update'    => 'licence-renewal.update',   'destroy'   => 'licence-renewal.destroy' 
                            ] 
                ]
            ); 
           
            
            
        });
        
        
        
            
            /*
            |--------------------------------------------------------------------------
            | Web Routes for breakage menu
            |--------------------------------------------------------------------------
            */
            Route::any('breakage-autocomplete', 'Breakage\BreakageController@ItemAutocomplete' ); 
            Route::any('breakage-list', 'Breakage\BreakageController@GetDataTableList' )->name('breakage_list'); 
            Route::bind('breakage', function ($value, $route) {return Modules\Master\Entities\Breakage::find($value); }); 
            Route::resource( '/breakage', 'Breakage\BreakageController',[ 
                'names' =>  [   
                                'index'     => 'breakage',          'create'    => 'breakage.create',
                                'store'     => 'breakage.store',    'edit'      => 'breakage.edit',
                                'update'    => 'breakage.update',   'destroy'   => 'breakage.destroy' 
                            ] 
                ]
            ); 
           
            Route::any('breakage-m-list', 'Breakage\MasBreakageController@GetDataTableList' )->name('breakage_m_list'); 
            Route::bind('breakage-m', function ($value, $route) {return Modules\Master\Entities\Breakage::find($value); }); 
            Route::resource( '/breakage-m', 'Breakage\MasBreakageController',[ 
                'names' =>  [   
                                'index'     => 'breakage-m', 'create'    => 'breakage-m.create',
                                'store'     => 'breakage-m.store',    'edit'      => 'breakage-m.edit',
                                'update'    => 'breakage-m.update',   'destroy'   => 'breakage-m.destroy' 
                            ] 
                ]
            );
        
            /*
            |--------------------------------------------------------------------------
            | Web Routes for gate-pass in breakage menu
            |--------------------------------------------------------------------------
            */
            Route::any('gate-pass-list', 'GatePass\GatePassController@GetDataTableList' )->name('gate_pass_list'); 
            Route::bind('gate-pass', function ($value, $route) {return Modules\Master\Entities\GatePass::find($value); }); 
            Route::resource( '/gate-pass', 'GatePass\GatePassController',[ 
                'names' =>  [   
                                'index'     => 'gate-pass',          'create'    => 'gate-pass.create',
                                'store'     => 'gate-pass.store',    'edit'      => 'gate-pass.edit',
                                'update'    => 'gate-pass.update',   'destroy'   => 'gate-pass.destroy' 
                            ] 
                ]
            );
            
            
            Route::any('gate-pass-list-m', 'GatePass\MasGatePassController@GetDataTableList' )->name('gate_pass_list_m'); 
            Route::bind('gate-pass-m', function ($value, $route) {return Modules\Master\Entities\GatePass::find($value); }); 
            Route::resource( '/gate-pass-m', 'GatePass\MasGatePassController',[ 
                'names' =>  [   
                                'index'     => 'gate-pass-m',          'create'    => 'gate-pass-m.create',
                                'store'     => 'gate-pass-m.store',    'edit'      => 'gate-pass-m.edit',
                                'update'    => 'gate-pass-m.update',   'destroy'   => 'gate-pass-m.destroy' 
                            ] 
                ]
            );
            
          
      
        
        /*
        |--------------------------------------------------------------------------
        | Web Routes for Reports menu
        |--------------------------------------------------------------------------
        */ 
        Route::any('reports', 'Reports\ReportController@index' )->name('reports_list');
        // Stock Register
        Route::any('stock-reports/{slug}', 'Reports\StockRegisterController@index' )->name('stock_reports'); 
        //Breakage Reports
        Route::any('breakage-reports/{slug}', 'Reports\BreakageReportsController@index' )->name('breakage_reports'); 
        //Breakdown Reports
        Route::any('breakdown-reports/{slug}', 'Reports\BreakdownReportsController@index' )->name('breakdown_reports'); 
        //Consumption Reports
        Route::any('consumption-reports/{slug}', 'Reports\ConsumptionReportsController@index' )->name('consumption_reports'); 
        Route::any('item-autocomplete', 'Reports\ConsumptionReportsController@ItemAutocomplete' ); 
        //Maintenance Reports
        Route::any('maintenance-reports/{slug}', 'Reports\MaintenanceReportsController@index' )->name('maintenance_reports'); 
        //Calibration Reports
        Route::any('calibration-reports/{slug}', 'Reports\CalibrationReportsController@index' )->name('calibration_reports'); 
        //Expiry Reports
        Route::any('expiry-reports/{slug}', 'Reports\ExpiryReportsController@index' )->name('expiry_reports'); 
        //License  Reports
        Route::any('license-reports/{slug}', 'Reports\LicenseReportsController@index' )->name('license_reports'); 
        //Gate Pass Reports
//        Route::any('gate-pass-reports/{slug}', 'Reports\GatePassReportsController@index' )->name('gate_pass_reports'); 
        
       
       
        
    });
    
    
        /*
        |--------------------------------------------------------------------------
        | Web Routes for cron job 
        |--------------------------------------------------------------------------
        */
        Route::name('cron.')->group( function()
        {
            
           Route::any('calibration-job', 'Cron\CalibrationJobController@index' )->name('calibration-job');
           Route::any('calibration-started', 'Cron\CalibrationJobController@show' )->name('calibration-job-started');
             
        });
        
    
});


/*
|--------------------------------------------------------------------------
| Download Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' =>  'master_auth:'.master_guard], function()
{
    // Stock Register
    Route::any('stock-reports-download/{slug}', 'Reports\StockRegisterController@create' )->name('stock_reports_download');
    //Breakage Reports
    Route::post('breakage-reports-download/{slug}', 'Reports\BreakageReportsController@create' )->name('breakage_reports_download');
    //Breakdown Reports
    Route::post('breakdown-reports-download/{slug}', 'Reports\BreakdownReportsController@create' )->name('breakdown_reports_download');
    //Consumption Reports
    Route::post('consumption-reports-download/{slug}', 'Reports\ConsumptionReportsController@create' )->name('consumption_reports_download');
    //Maintenance Reports
    Route::post('maintenance-reports-download/{slug}', 'Reports\MaintenanceReportsController@create' )->name('maintenance_reports_download');
    //Calibration Reports
    Route::post('calibration-reports-download/{slug}', 'Reports\CalibrationReportsController@create' )->name('calibration_reports_download');
    //Expiry Reports
    Route::any('expiry-reports-download/{slug}', 'Reports\ExpiryReportsController@create' )->name('expiry_reports_download');
    //License Reports
    Route::any('license-reports-download/{slug}', 'Reports\LicenseReportsController@create' )->name('license_reports_download');
    //Gate Pass Reports
    Route::any('gate-pass-reports-download/{slug}', 'Reports\GatePassReportsController@create' )->name('gate_pass_reports_download');
   
    
}); 