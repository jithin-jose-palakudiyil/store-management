<div class="navbar navbar-default" id="navbar-second">
    <ul class="nav navbar-nav no-border visible-xs-block">
            <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-menu7"></i></a></li>
    </ul>
    <?php   $GroupRouteName = \Route::currentRouteName(); 
            $GroupRouteName = substr($GroupRouteName, 0, strpos( $GroupRouteName, '.')); 
//            $AuthPermissions= \Auth::guard(master_guard)->user()->belongsToManyPermissions;
//            $AuthPermissions->where()->first();
        $AuthPermissions=\Modules\Master\Entities\Auth::with(array('belongsToManyPermissions' => function($query) {
            $query->with('hasModule');
        }))->where('id',\Auth::guard(master_guard)->user()->id)->first();
        $AuthPermissions=$AuthPermissions->belongsToManyPermissions->pluck('hasModule.slug')->unique()->all();
         
    ?>
    <div class="navbar-collapse collapse" id="navbar-second-toggle">
        <ul class="nav navbar-nav">
            <li <?php echo (isset($active) && $active == 'dashboard') ? 'class=active' : ''; ?> ><a href="{{route('master_dashboard')}}"><i class="icon-display4 position-left"></i> Dashboard</a></li>
            
            
            
            <?php //dd($AuthPermissions); ?>
            @if (in_array("item-category", $AuthPermissions) 
             || in_array("item", $AuthPermissions) 
             || in_array("indents", $AuthPermissions) || in_array("purchase-entry", $AuthPermissions) 
             || in_array("store", $AuthPermissions) 
            || Auth::guard(master_guard)->user()->is_developer==1 )
            <li class="dropdown" <?php echo (isset($GroupRouteName) && $GroupRouteName == 'stock') ? 'class=active' : ''; ?>>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class=" icon-law position-left"></i>Stock <span class="caret"></span>
                </a>

                <ul class="dropdown-menu width-200"> 
                    @if (in_array("item-category", $AuthPermissions) || in_array("item", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1 )
                    <li class="dropdown-submenu dropdown-submenu-hover" <?php echo (isset($active) && ($active == 'item_category' || $active == 'items')) ? 'class=active' : ''; ?>>
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu2"></i>Items & Category</a>
                        <ul class="dropdown-menu">
                            @if (in_array("item-category", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1 )
                            <li <?php echo (isset($active) && $active == 'item_category') ? 'class=active' : ''; ?>><a href="{{route('stock.item-category')}}">Category</a></li> 
                            @endif
                            @if (in_array("item", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1 )
                            <li <?php echo (isset($active) && $active == 'items') ? 'class=active' : ''; ?>><a href="{{route('stock.items')}}">Items</a></li> 
                            @endif
                        </ul>
                    </li>
                    @endif 
                    @if (in_array("store", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1)
                     <li <?php echo (isset($active) && $active == 'store') ? 'class=active' : ''; ?>><a href="{{route('stock.store')}}"><i class="icon-store"></i>Store</a></li>
                    @endif
                    @if (in_array("purchase-entry", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1)
                     <li <?php echo (isset($active) && $active == 'purchase-entry') ? 'class=active' : ''; ?>><a href="{{route('stock.purchase-entry')}}"><i class=" icon-enlarge5"></i>Purchase Entry</a></li>
                    @endif
                   
                        @if (in_array("indents", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1)
                         <li <?php echo (isset($active) && $active == 'indents') ? 'class=active' : ''; ?>><a href="{{route('stock.indents')}}"><i class="  icon-toggle"></i>Indents</a></li>
                        @endif
                    
                </ul>
            </li>
            @endif 
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            @if (in_array("measurements", $AuthPermissions) 
            || in_array("maintenance-type", $AuthPermissions) 
            || in_array("users", $AuthPermissions) 
            || in_array("calibration-type", $AuthPermissions) 
            || in_array("suppliers", $AuthPermissions)  
            || Auth::guard(master_guard)->user()->is_developer==1 )
            <li class="dropdown" <?php echo (isset($GroupRouteName) && $GroupRouteName == 'extras') ? 'class=active' : ''; ?>>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class=" icon-circles2 position-left"></i> Extras <span class="caret"></span>
                </a>

                <ul class="dropdown-menu width-200">
                    @if (in_array("measurements", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1 )
                        <li <?php echo (isset($active) && $active == 'measurements') ? 'class=active' : ''; ?>><a href="{{route('extras.measurements')}}"><i class="icon-rulers"></i>Measurements</a></li>
                    @endif
                    @if (in_array("maintenance-type", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1)
                     <li <?php echo (isset($active) && $active == 'maintenance_type') ? 'class=active' : ''; ?>><a href="{{route('extras.maintenance-type')}}"><i class="icon-fence"></i>Maintenance Type</a></li>
                    @endif
                    
                    
                    @if(Auth::guard(master_guard)->user()->is_developer==1)
                    <li class="dropdown-header">Auth Menus</li> 
                    <li class="dropdown-submenu dropdown-submenu-hover" <?php echo (isset($active) && ($active == 'permission' || $active == 'roles')) ? 'class=active' : ''; ?>>
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-lock2"></i>Authorization</a>
                        <ul class="dropdown-menu">
                            <li <?php echo (isset($active) && $active == 'module') ? 'class=active' : ''; ?>><a href="{{route('extras.module')}}">Modules</a></li> 
                            <li <?php echo (isset($active) && $active == 'permission') ? 'class=active' : ''; ?>><a href="{{route('extras.permissions')}}">Permissions</a></li>      
                        </ul>
                    </li>
                    @endif
                    
                    @if (in_array("users", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1 )
                        <li <?php echo (isset($active) && $active == 'users') ? 'class=active' : ''; ?>><a href="{{route('extras.users')}}"><i class="icon-users"></i>Users</a></li>
                    @endif
                    
                    @if (in_array("calibration-type", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1)
                     <li <?php echo (isset($active) && $active == 'calibration_type') ? 'class=active' : ''; ?>><a href="{{route('extras.calibration-type')}}"><i class="icon-hour-glass2"></i>Calibration Type</a></li>
                    @endif
                    
                    @if (in_array("suppliers", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1)
                     <li <?php echo (isset($active) && $active == 'suppliers') ? 'class=active' : ''; ?>><a href="{{route('extras.suppliers')}}"><i class="icon-store"></i>Suppliers</a></li>
                    @endif
                </ul>
            </li>
            @endif     
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
           
            @if (in_array("maintenance", $AuthPermissions) 
            || in_array("calibration", $AuthPermissions) 
            || in_array("licence-renewal", $AuthPermissions) 
             || in_array("barcode", $AuthPermissions) 
            || Auth::guard(master_guard)->user()->is_developer==1 )
            <li class="dropdown" <?php echo (isset($GroupRouteName) && $GroupRouteName == 'others') ? 'class=active' : ''; ?>>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class=" icon-rotate-ccw3 position-left"></i>Others <span class="caret"></span>
                </a>
                <ul class="dropdown-menu width-200">
                    @if (in_array("maintenance", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1)
                     <li <?php echo (isset($active) && $active == 'maintenance') ? 'class=active' : ''; ?>><a href="{{route('others.maintenance')}}"><i class=" icon-hammer-wrench"></i>Maintenance</a></li>
                    @endif
                    @if (in_array("calibration", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1)
                     <li <?php echo (isset($active) && $active == 'calibration') ? 'class=active' : ''; ?>><a href="{{route('others.calibration')}}"><i class="icon-balance"></i>Calibration</a></li>
                    @endif
                    @if (in_array("licence-renewal", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1)
                     <li <?php echo (isset($active) && $active == 'licence-renewal') ? 'class=active' : ''; ?>><a href="{{route('others.licence-renewal')}}"><i class="icon-loop3"></i>Licence Renewal</a></li>
                    @endif
                    
                     @if (in_array("barcode", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1)
                     <li <?php echo (isset($active) && $active == 'barcode') ? 'class=active' : ''; ?>><a href="{{route('stock.barcode_create')}}"><i class="icon-barcode2"></i>Barcode Read</a></li>
                    @endif
                </ul>
                    
            </li>
            @endif 
            
            
            
            @if (in_array("breakage", $AuthPermissions)  
            || Auth::guard(master_guard)->user()->is_developer==1 ) 
                @if(\Auth::guard(master_guard)->user()->role =='master')

                <li class="dropdown" <?php echo (isset($active) && $active == 'breakage') ? 'class=active' : ''; ?>>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class=" icon-page-break position-left"></i>Breakage / Breakdown <span class="caret"></span>
                </a>
                <ul class="dropdown-menu width-200">
                     
                    <li <?php echo (isset($active) && $active == 'breakage') ? 'class=active' : ''; ?>><a href="{{route('breakage')}}"><i class=" icon-hammer-wrench"></i>Breakage / Breakdown Store</a></li>
                   <li <?php echo (isset($active) && $active == 'breakage-m') ? 'class=active' : ''; ?>><a href="{{route('breakage-m')}}"><i class=" icon-hammer-wrench"></i>Breakage / Breakdown WareHouse</a></li>
                   
                   
                </ul>
                    
            </li>
                
                
                @else
                <li <?php echo (isset($active) && $active == 'breakage') ? 'class=active' : ''; ?> ><a href="{{route('breakage')}}"><i class="icon-page-break position-left"></i> Breakage / Breakdown</a></li>

                @endif 
            @endif 
            
             @if ( ( in_array("reports", $AuthPermissions) || Auth::guard(master_guard)->user()->is_developer==1 ) &&  \Auth::guard(master_guard)->user()->role =='master') 
            <li <?php echo (isset($active) && $active == 'reports') ? 'class=active' : ''; ?> ><a href="{{route('reports_list')}}"><i class=" icon-file-download2 position-left"></i> Reports</a></li>
              @endif 
            
                
<!--                <li class="dropdown mega-menu mega-menu-wide">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mega menu <span class="caret"></span></a>

                        <div class="dropdown-menu dropdown-content">
                                <div class="dropdown-content-body">
                                        <div class="row">
                                                <div class="col-md-3">
                                                        <span class="menu-heading underlined">Column 1 title</span>
                                                        <ul class="menu-list">
                                                                <li><a href="#">First link, first column</a></li>
                                                                <li>
                                                                        <a href="#">Second link (multilevel)</a>
                                                                        <ul>
                                                                                <li><a href="#">Second level, first link</a></li>
                                                                                <li><a href="#">Second level, second link</a></li>
                                                                                <li><a href="#">Second level, third link</a></li>
                                                                                <li><a href="#">Second level, fourth link</a></li>
                                                                        </ul>
                                                                </li>
                                                                <li><a href="#">Third link, first column</a></li>
                                                                <li><a href="#">Fourth link, first column</a></li>
                                                        </ul>
                                                </div>
                                                <div class="col-md-3">
                                                        <span class="menu-heading underlined">Column 2 title</span>
                                                        <ul class="menu-list">
                                                                <li><a href="#">First link, second column</a></li>
                                                                <li>
                                                                        <a href="#">Second link (multilevel)</a>
                                                                        <ul>
                                                                                <li><a href="#">Second level, first link</a></li>
                                                                                <li><a href="#">Second level, second link</a></li>
                                                                                <li><a href="#">Second level, third link</a></li>
                                                                                <li><a href="#">Second level, fourth link</a></li>
                                                                        </ul>
                                                                </li>
                                                                <li><a href="#">Third link, second column</a></li>
                                                                <li><a href="#">Fourth link, second column</a></li>
                                                        </ul>
                                                </div>
                                                <div class="col-md-3">
                                                        <span class="menu-heading underlined">Column 3 title</span>
                                                        <ul class="menu-list">
                                                                <li><a href="#">First link, third column</a></li>
                                                                <li>
                                                                        <a href="#">Second link (multilevel)</a>
                                                                        <ul>
                                                                                <li><a href="#">Second level, first link</a></li>
                                                                                <li><a href="#">Second level, second link</a></li>
                                                                                <li><a href="#">Second level, third link</a></li>
                                                                                <li><a href="#">Second level, fourth link</a></li>
                                                                        </ul>
                                                                </li>
                                                                <li><a href="#">Third link, third column</a></li>
                                                                <li><a href="#">Fourth link, third column</a></li>
                                                        </ul>
                                                </div>
                                                <div class="col-md-3">
                                                        <span class="menu-heading underlined">Column 4 title</span>
                                                        <ul class="menu-list">
                                                                <li><a href="#">First link, fourth column</a></li>
                                                                <li>
                                                                        <a href="#">Second link (multilevel)</a>
                                                                        <ul>
                                                                                <li><a href="#">Second level, first link</a></li>
                                                                                <li><a href="#">Second level, second link</a></li>
                                                                                <li><a href="#">Second level, third link</a></li>
                                                                                <li><a href="#">Second level, fourth link</a></li>
                                                                        </ul>
                                                                </li>
                                                                <li><a href="#">Third link, fourth column</a></li>
                                                                <li><a href="#">Fourth link, fourth column</a></li>
                                                        </ul>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </li>

                <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                Starter kit <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu width-200">
                                <li class="dropdown-header">Basic layouts</li>
                                <li class="dropdown-submenu dropdown-submenu-hover">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-grid2"></i> Columns</a>
                                        <ul class="dropdown-menu">
                                                <li class="dropdown-header highlight">Options</li>
                                                <li><a href="1_col.html">One column</a></li>
                                                <li><a href="2_col.html">Two columns</a></li>
                                                <li class="dropdown-submenu dropdown-submenu-hover">
                                                        <a href="#">Three columns</a>
                                                        <ul class="dropdown-menu">
                                                                <li class="dropdown-header highlight">Sidebar position</li>
                                                                <li><a href="3_col_dual.html">Dual sidebars</a></li>
                                                                <li><a href="3_col_double.html">Double sidebars</a></li>
                                                        </ul>
                                                </li>
                                                <li><a href="4_col.html">Four columns</a></li>
                                        </ul>
                                </li>
                                <li class="dropdown-submenu dropdown-submenu-hover">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-paragraph-justify3"></i> Navbars</a>
                                        <ul class="dropdown-menu">
                                                <li class="dropdown-header highlight">Fixed navbars</li>
                                                <li><a href="layout_navbar_fixed_main.html">Fixed main navbar</a></li>
                                                <li><a href="layout_navbar_fixed_secondary.html">Fixed secondary navbar</a></li>
                                                <li class="active"><a href="layout_navbar_fixed_both.html">Both navbars fixed</a></li>
                                        </ul>
                                </li>
                                <li class="dropdown-header">Optional layouts</li>
                                <li><a href="layout_boxed.html"><i class="icon-align-center-horizontal"></i> Fixed width</a></li>
                                <li><a href="layout_sidebar_sticky.html"><i class="icon-flip-vertical3"></i> Sticky sidebar</a></li>
                        </ul>
                </li>-->
        </ul>

            <ul class="nav navbar-nav navbar-right">
                <?php  if(isset($CreateBtn['btn_txt']) && isset($CreateBtn['url'])): ?>
                    <a href="{{$CreateBtn['url']}}">
                        <div class="heading-elements">
                            <div class="heading-btn-group">
                            <button type="button" class="btn bg-primary-400 btn-labeled btn-labeled-left rounded-round"><b><i class="icon-plus-circle2"></i></b> {{$CreateBtn['btn_txt']}}</button>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
                    <li>
                    </li>

<!--                    <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-cog3"></i>
                                    <span class="visible-xs-inline-block position-right">Dropdown</span>
                                    <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="#">Action</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">One more separated line</a></li>
                            </ul>
                    </li>-->
            </ul>
    </div>
</div>