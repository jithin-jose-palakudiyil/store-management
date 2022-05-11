@extends('master::layouts.master')

@section('content')
    






 


<div class="row">     
    <?php 
    $sort ='+'.diff_in_days.' day';
    $DaysLater = date ('Y-m-d', strtotime ($sort));
    $PivotMaintenance = \Modules\Master\Entities\PivotMaintenance::select('maintenance.id as maintenance_id','pivot_maintenance.*','batch_items.unique_id')
            ->where('pivot_maintenance.date','<=',$DaysLater)->where('pivot_maintenance.status',1)
            ->join('maintenance','maintenance.id','=', 'pivot_maintenance.maintenance_id')
            ->join('batch_items','batch_items.id','=', 'maintenance.batch_item_id')
            ->join('store_items_list','store_items_list.item_id','=', 'batch_items.item_id') 
            ->where('store_items_list.store_id',\Auth::guard(master_guard)->user()->store_id)
            ->orderBy('pivot_maintenance.date', 'DESC')
            ->limit(4)->get();  

    ?>
    <!-- List of latest Maintenance -->
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
    </div>
    <!-- /list of maintenance updates -->
    
    
    
     <!-- list of Calibration updates -->
    <?php 
                 
        $PivotCalibration = Modules\Master\Entities\PivotCalibration::select('calibration.id as calibration_id','pivot_calibration.*','batch_items.unique_id')  
                ->where('pivot_calibration.date','<=',$DaysLater)->where('pivot_calibration.status',0)
                ->join('calibration','calibration.id','=', 'pivot_calibration.calibration_id')
                ->join('batch_items','batch_items.id','=', 'calibration.batch_item_id')
                ->join('store_items_list','store_items_list.item_id','=', 'batch_items.item_id') 
                ->where('store_items_list.store_id',\Auth::guard(master_guard)->user()->store_id)
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
                ->join('store_items_list','store_items_list.item_id','=', 'batch_items.item_id') 
                ->where('store_items_list.store_id',\Auth::guard(master_guard)->user()->store_id)
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
        $Indents =  Modules\Master\Entities\Indents::select('indents.*')  
                    ->where('indents.request_from', \Auth::guard(master_guard)->user()->store_id)
                    ->orWhere('indents.request_to', \Auth::guard(master_guard)->user()->store_id) 
                    ->orderBy('indents.created_at', 'DESC')->limit(4)
                    ->get();   
         
    ?> 
    <!-- List of latest indents -->
    <div class="col-md-6">      
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h6 class="panel-title">Indent Request</h6> 
                <div class="heading-elements">
                <!--<a href="#" class="heading-text">See all &rarr;</a>-->
                </div>
            </div>  
            <div class="panel-body" style="height: 320px">
                <?php if(count($Indents) > 0):  ?> 
                <ul class="media-list">
                     <?php foreach ($Indents as $key_I => $value_I): 
                        $find = $txt = null;
                        if($value_I->request_from==\Auth::guard(master_guard)->user()->store_id) : 
                            if($value_I->request_to != null): $find =$value_I->request_to; endif; 
                        elseif($value_I->request_to==\Auth::guard(master_guard)->user()->store_id) :
                            $find =$value_I->request_from;
                        endif;
                            $store= Modules\Master\Entities\Store::find($find); 
                            $txt ='<code>(Indent ID:'.$value_I->id.') </code>';
                            if($value_I->authority_status==0 && ($value_I->request_to == \Auth::guard(master_guard)->user()->store_id)):
                                 $txt.='<a href="#">Authority</a> not processed the indent items form you requested ';
                            elseif($value_I->authority_status==0 && ($value_I->request_from == \Auth::guard(master_guard)->user()->store_id)):
                                 $txt.='<a href="#">Authority</a> not processed the indent requested';
                            elseif($value_I->authority_status==1 && $value_I->to_status==0):
                                $txt.='<a href="#">Authority</a> processed the indent requested ';  
                            elseif($value_I->authority_status==1 && $value_I->to_status==1):
                                if($value_I->request_to == null && $store == null): 
                                    $txt.='<a href="#">WareHouse</a> processed the indent requested ';
                                else:
                                     $txt.='Indent processed successfully requested ';
                                endif; 
                            endif;
                            
                            if($value_I->request_to == null &&  ($value_I->request_from==\Auth::guard(master_guard)->user()->store_id)): 
                                $txt.=' by you';
                            elseif($value_I->request_to == \Auth::guard(master_guard)->user()->store_id): 
                                $txt.=' by '.$store->name;
                            elseif($value_I->request_from == \Auth::guard(master_guard)->user()->store_id): 
                               $txt.=' by you';
                            endif;
                            if($value_I->authority_status==1 && $value_I->to_status==0 && $value_I->request_to == \Auth::guard(master_guard)->user()->store_id):
                                $txt.=', Check indents request recived';
                            endif;
                                
                           
                        ?> 
                    <li class="media"> 
                        <div class="media-body">
                            {!!$txt!!} 
                            <div class="media-annotation"><?php echo \Carbon\Carbon::parse($value_I->created_at)->diffForHumans(); ?></div>
                        </div>
                    </li> 
                      <?php  endforeach; ?>
                </ul>
                <?php else:?>
                        Sorry, no indent notifications found!
                <?php endif; ?>
            </div>
        </div> 
    </div>
    <!-- /list of latest indents --> 
    
    <!-- List of latest Breakage -->
    <?php
    
    $Breakage = Modules\Master\Entities\Breakage::select('breakage.*','batch_items.unique_id','store.name as store_name')  
                ->where('breakage.step','!=',0) 
                ->join('pivot_store_items','pivot_store_items.id','=', 'breakage.pivot_store_item_id')
                ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
                ->join('store','store.id','=', 'breakage.store_id')
                ->where('breakage.store_id',\Auth::guard(master_guard)->user()->store_id)
                ->orderBy('breakage.created_at', 'DESC')->limit(4)
                ->get(); 
//    dd($Breakage);
    ?>
    <div class="col-md-6">   
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
                    <?php foreach ($Breakage as $key_b => $value_b): 
//                        dd($value_b);
                        $txt ='<code>(Breakage ID:'.$value_b->id.') </code>';
                        if($value_b->step==1):
                             $txt.='<a href="#">Authority</a> is processed the breakage requested by you ';
                        elseif($value_b->step==2):
                             $txt.='<a href="#">Authority</a> is closed the breakage requested by you ';
                        elseif($value_b->step==3):
                             $txt.='<a href="#">Authority</a> is rejected the breakage requested by you ';
                        endif;
                        ?> 
                    <li class="media"> 
                        <div class="media-body">
                            {!!$txt!!} 
                            <div class="media-annotation"><?php echo \Carbon\Carbon::parse($value_b->created_at)->diffForHumans(); ?></div>
                        </div>
                    </li> 
                    <?php  endforeach; ?>
                </ul>
                <?php else:?>
                        Sorry, no breakage notifications found!
                <?php endif; ?>
            </div>
        </div> 
    </div>   
    <!-- /list of latest Breakage -->
 </div>






@endsection
