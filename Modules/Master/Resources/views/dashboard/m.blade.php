@extends('master::layouts.master')

@section('content')
<!-- Counts of modules -->
<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="panel panel-body panel-body-accent">
            <div class="media no-margin">
                <div class="media-left media-middle">
                    <i class="icon-pointer icon-3x text-success-400"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="no-margin text-semibold">
                        <?php $TOTAL_ITEMS = \Modules\Master\Entities\Items::all()->count(); ?>
                        {{$TOTAL_ITEMS}}
                    </h3>
                    <span class="text-uppercase text-size-mini text-muted">total Items</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="panel panel-body">
            <div class="media no-margin">
                <div class="media-left media-middle">
                    <i class="icon-enter6 icon-3x text-indigo-400"></i>
                </div> 
                <div class="media-body text-right">
                    <h3 class="no-margin text-semibold">
                        <?php $TOTAL_USERS = \Modules\Master\Entities\Auth::where('is_developer','!=',1)->get()->count(); ?>
                        {{$TOTAL_USERS}}
                    </h3>
                    <span class="text-uppercase text-size-mini text-muted">total Users</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="panel panel-body">
            <div class="media no-margin">
                <div class="media-body">
                    <h3 class="no-margin text-semibold">
                        <?php $TOTAL_SUPPLIERS = \Modules\Master\Entities\Suppliers::all()->count(); ?>
                        {{$TOTAL_SUPPLIERS}}
                    </h3>
                    <span class="text-uppercase text-size-mini text-muted">total Suppliers</span>
                </div> 
                <div class="media-right media-middle">
                    <i class="icon-bubbles4 icon-3x text-blue-400"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="panel panel-body">
            <div class="media no-margin">
                <div class="media-body">
                    <h3 class="no-margin text-semibold">
                        <?php $TOTAL_STORE = \Modules\Master\Entities\Store::all()->count(); ?>
                        {{$TOTAL_STORE}}
                    </h3>
                    <span class="text-uppercase text-size-mini text-muted">total Store</span>
                </div> 
                <div class="media-right media-middle">
                    <i class="icon-bag icon-3x text-danger-400"></i>
                </div>
            </div>
        </div>
    </div>
    
</div>
<!-- End counts of modules -->





<!-- List of files -->
<div class="row">
<!--    <div class="col-md-12">
						<div class="panel panel-white">
							<div class="panel-heading">
								<h6 class="text-semibold panel-title">
									<i class="icon-folder6 position-left"></i>
									Notifications 
								</h6>

								<div class="heading-elements">
									<span class="heading-text text-muted">(93)</span>
								</div>
							</div>

							<div class="list-group no-border">
								<a href="#" class="list-group-item">
									<i class="icon-file-pdf"></i> Hello_exotic_staunch.pdf <span class="label bg-success-400">New</span>
								</a>

								<a href="#" class="list-group-item">
									<i class="icon-file-word"></i> That_well_ecstatically.docx
								</a>

								<a href="#" class="list-group-item">
									<i class="icon-file-excel"></i> Shared_coast_concurrent.csv <span class="label bg-slate">Draft</span>
								</a>

								<a href="#" class="list-group-item">
									<i class="icon-file-word"></i> Into_intrepid_belated.docx
								</a>

								<a href="#" class="list-group-item">
									<i class="icon-arrow-right22"></i> Show all files (93)
								</a>
							</div>
						</div></div>-->
</div>
<!-- /list of files -->




<div class="row"> 
    <!-- list of maintenance updates -->
                <?php 
                $sort ='+'.diff_in_days.' day';
                $DaysLater = date ('Y-m-d', strtotime ($sort));
                $PivotMaintenance = \Modules\Master\Entities\PivotMaintenance::select('maintenance.id as maintenance_id','pivot_maintenance.*','batch_items.unique_id')
                        ->where('pivot_maintenance.date','<=',$DaysLater)->where('pivot_maintenance.status',0)
                        ->join('maintenance','maintenance.id','=', 'pivot_maintenance.maintenance_id')
                        ->join('batch_items','batch_items.id','=', 'maintenance.batch_item_id')
                        ->orderBy('pivot_maintenance.date', 'DESC')->limit(4)->get();  
                ?>
    <div class="col-md-4"> 
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h6 class="panel-title">Maintenance</h6> 
                <div class="heading-elements">
                    <!--<a href="#" class="heading-text">See all &rarr;</a>-->
                </div>
            </div> 
            <div class="panel-body" style="height: 320px">
                    <?php if(count($PivotMaintenance) >0):  ?>
                        <ul class="media-list">
                            <?php foreach ($PivotMaintenance as $key => $value): 
                                $UID = $value->unique_id ;$text = $coming_pending = null;
                                $date=date_create($value->date); 
                                $_today = \Carbon\Carbon::createFromFormat('Y-m-d', date ('Y-m-d'));
                                $_nextDay =\Carbon\Carbon::createFromFormat('Y-m-d', date_format($date,"Y-m-d"));
                                $diffInDays = $_nextDay->diffInDays($_today); 
                                //pending maintenance
                                if($date < $_today)   :  $text ='Item maintenance is pending ';$coming_pending = ($diffInDays!=0)? $diffInDays.' days due' : 'due today' ; endif;  
                                //coming maintenance
                                if($date >= $_today)   : $text ='Item maintenance is coming ';$coming_pending = $diffInDays.' days left'; endif;

                                if($UID && $text && $coming_pending ): ?>
                                    <li class="media">
                                        <div class="media-body">
                                            <a href="#">{{$UID}}</a> {{$text}}
                                            <div class="media-annotation">{{$coming_pending}}</div>
                                        </div>
                                        <hr/>
                                    </li>  
                                <?php endif; ?> 
                            <?php endforeach;  ?>
                        </ul>
                    <?php else:?>
                        Sorry, no maintenance is pending
                    <?php endif;?> 
            </div>
        </div>
    <!-- /list of maintenance updates -->
    </div>
    <!-- /list of maintenance updates -->
    
    <!-- list of Calibration updates -->
    <?php 
                 
        $PivotCalibration = Modules\Master\Entities\PivotCalibration::select('calibration.id as calibration_id','pivot_calibration.*','batch_items.unique_id')  
                ->where('pivot_calibration.date','<=',$DaysLater)->where('pivot_calibration.status',0)
                ->join('calibration','calibration.id','=', 'pivot_calibration.calibration_id')
                ->join('batch_items','batch_items.id','=', 'calibration.batch_item_id')
                ->orderBy('pivot_calibration.date', 'DESC')->limit(4)
                ->get();     
    ?> 
    <div class="col-md-4">      
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h6 class="panel-title">Calibration</h6> 
                <div class="heading-elements">
                    <!--<a href="#" class="heading-text">See all &rarr;</a>-->
                </div>
            </div> 
            <div class="panel-body" style="height: 320px">
                <?php if(count($PivotCalibration) >0):   ?>  
                <ul class="media-list">
                    <?php foreach ($PivotCalibration as $key_C => $value_C): 
                        $UID_C = $value_C->unique_id ;$text_C = $coming_pending_C = null;
                        $date_C=date_create($value_C->date); 
                        $_today_C = \Carbon\Carbon::createFromFormat('Y-m-d', date ('Y-m-d'));
                        $_nextDay_C =\Carbon\Carbon::createFromFormat('Y-m-d', date_format($date_C,"Y-m-d"));
                        $diffInDays_C = $_nextDay_C->diffInDays($_today_C); 
                        //pending maintenance
                        if($date_C < $_today_C)   :  $text_C ='Item calibration is pending ';$coming_pending_C =($diffInDays_C!=0) ? $diffInDays_C.' days due' : 'due today'; endif;  
                        //coming maintenance
                        if($date_C >= $_today_C)   : $text_C ='Item calibration is coming ';$coming_pending_C = $diffInDays_C.' days left'; endif;

                        if($UID_C && $text_C && $coming_pending_C ): ?>
                            <li class="media">
                                <div class="media-body">
                                    <a href="#">{{$UID_C}}</a> {{$text_C}}
                                    <div class="media-annotation">{{$coming_pending_C}}</div>
                                </div>
                                <hr/>
                            </li>  
                        <?php endif; ?> 
                   <?php endforeach;  ?> 
                </ul>
                <?php else:?>
                        Sorry, no calibration is pending
                <?php endif;  ?>
            </div>
        </div> 
    </div> 
    <!-- /list of Calibration updates -->
    
    <!-- list of Licence Renewal updates -->
    <?php 
                 
        $LicenceRenewal = Modules\Master\Entities\LicenceRenewal::
                select('licence_renewal.*','batch_items.unique_id')  
                ->where('licence_renewal.expiry_date','<=',$DaysLater)
                ->where('licence_renewal.status',0) 
                ->join('batch_items','batch_items.id','=', 'licence_renewal.batch_item_id')
                ->orderBy('licence_renewal.expiry_date', 'DESC')->limit(4)
                ->get();    
    ?> 
    <div class="col-md-4">   
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h6 class="panel-title">Licence Renewal</h6> 
                <div class="heading-elements">
                    <!--<a href="#" class="heading-text">See all &rarr;</a>-->
                </div>
            </div> 
            <div class="panel-body" style="height: 320px">
                <?php if(count($LicenceRenewal) >0):   ?>  
                    <ul class="media-list">
                        <?php foreach ($LicenceRenewal as $key_L => $value_L): 
                            $UID_L = $value_L->unique_id ;$text_L = $coming_pending_L = null; 
                            
                            $_today_L = \Carbon\Carbon::createFromFormat('Y-m-d', date ('Y-m-d'));
                            $date_L =\Carbon\Carbon::createFromFormat('Y-m-d', date_format(date_create($value_L->expiry_date),"Y-m-d"));
                            $diffInDays_L = $date_L->diffInDays($_today_L);
                        
                            //pending licence_renewal
                           if($date_L < $_today_L)   :  $text_L ='Items licence renewal is pending ';$coming_pending_L =($diffInDays_L!=0) ? $diffInDays_L.' days due' : 'due today'; endif;  
                           //coming licence_renewal
                           if($date_L >= $_today_L)   : $text_L ='Items licence renewal is coming ';$coming_pending_L = $diffInDays_L.' days left'; endif;

                        
                         if($UID_L && $text_L && $coming_pending_L ): ?>
                            <li class="media">
                                <div class="media-body">
                                    <a href="#">{{$UID_L}}</a> {{$text_L}}
                                    <div class="media-annotation">{{$coming_pending_L}}</div>
                                </div>
                                <hr/>
                            </li>  
                        <?php endif; ?>    
                        <?php  endforeach; ?>
                    </ul>
                <?php else: ?>
                        Sorry, no renewal is pending
                <?php endif;   ?>  
            </div>
        </div>
						 

    </div> 
    <!-- /list of Licence Renewal updates -->
    
    
    
 </div>
















 <div class="row">     
   <!-- List of latest Indents -->
    <?php           
        $Indents = Modules\Master\Entities\Indents::select('indents.*','store.name as store_name')  
                ->where('indents.status',0)
                ->where('indents.authority_status',0)
                ->join('store','store.id','=', 'indents.request_from') 
                ->orderBy('indents.created_at', 'DESC')->limit(4)
                ->get();   
        
    ?> 
    <div class="col-md-6"> 
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h6 class="panel-title">Indents</h6> 
                <div class="heading-elements">
                    <!--<a href="#" class="heading-text">See all &rarr;</a>-->
                </div>
            </div> 
            <div class="panel-body" style="height: 320px">
                <?php if(count($Indents) > 0):  ?> 
                    
                        <ul class="media-list">
                            <?php foreach ($Indents as $key_I => $value_I): ?> 
                            <li class="media"> 
                                <div class="media-body">
                                    <a href="#">{{$value_I->store_name}}</a> requested a indent for approval having ID <code>{{$value_I->id}}</code>
                                    <div class="media-annotation"><?php echo \Carbon\Carbon::parse($value_I->created_at)->diffForHumans(); ?></div>
                                </div>
                            </li>   
                              <?php  endforeach; ?>
                        </ul>
                  
                 <?php else:?>
                        Sorry, no indent is pending
                <?php endif; ?>
            </div>
        </div> 
    </div>
    <!-- /list of latest Indents -->
    
    <!-- List of latest Breakage -->
    <div class="col-md-6">                                           
    <?php           
        $Breakage = Modules\Master\Entities\Breakage::select('breakage.*','batch_items.unique_id','store.name as store_name')  
                ->where('breakage.step',0) 
                ->join('pivot_store_items','pivot_store_items.id','=', 'breakage.pivot_store_item_id')
                ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
                ->join('store','store.id','=', 'breakage.store_id')
                ->orderBy('breakage.created_at', 'DESC')->limit(4)
                ->get(); 
         
        
    ?> 
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h6 class="panel-title">Breakage</h6> 
                <div class="heading-elements">
                    <!--<a href="#" class="heading-text">See all &rarr;</a>-->
                </div>
            </div>

            <div class="panel-body" style="height: 320px">
                <?php if(count($Breakage) > 0):  ?> 
                    <ul class="media-list">
                         <?php foreach ($Breakage as $key_b => $value_b): ?> 
                        <li class="media"> 
                            <div class="media-body">
                                <a href="#">{{$value_b->store_name}}</a> reported a breakage
                                <div class="media-annotation"><?php echo \Carbon\Carbon::parse($value_b->created_at)->diffForHumans(); ?></div>
                            </div>
                        </li>  
                         <?php  endforeach; ?>
                    </ul>
                <?php else:?>
                        Sorry, no breakage is reported
                <?php endif; ?>
            </div>
        </div>
						

     </div> 
    <!-- /list of latest Breakage -->
 </div>






@endsection
