<div id="modal_item_delete" class="modal fade" tabindex="-1">
     
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="icon-menu7"></i> &nbsp;
                    {{ isset($item->name) ? $item->name : '' }}
                </h5>
            </div> 
            <div class="modal-body">
                <?php 
                $batch_items =[];
                
                if(\Auth::guard(master_guard)->user()->role=='master'):
                    $batch_items = \Modules\Master\Entities\BatchItems::select('batch_items.*')
                        ->where("batch_items.whs_breakage",0)
                        ->where("batch_items.item_id",$item->id)
                        ->whereNull("batch_items.deleted_at")
                        ->whereNotIn('batch_items.id', function ($query) use($item) {
                            $query->select('batch_items.id')
                                    ->from('pivot_store_items')
                                    ->join('store_items_list','pivot_store_items.store_item_id','=', 'store_items_list.id')
                                    ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
                                    ->where("store_items_list.item_id",$item->id)
                                    ->where("pivot_store_items.is_recived",1);
//                                    ->whereNull("pivot_store_items.deleted_at");
                        })->get();
                elseif(\Auth::guard(master_guard)->user()->role=='store'): 
                    $batch_items = \Modules\Master\Entities\StoreItemsList::select('batch_items.*','pivot_store_items.id as pivot_store_id')
                        ->join('pivot_store_items','pivot_store_items.store_item_id','=', 'store_items_list.id')
                        ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
                        ->where("store_items_list.item_id",$item->id)
                        ->where("store_items_list.store_id",\Auth::guard(master_guard)->user()->store_id)
                        ->where("pivot_store_items.is_recived",1)
                        ->where("pivot_store_items.is_breakage",0)
                        ->where("batch_items.whs_breakage",0)
                        ->whereNull("batch_items.deleted_at")
                        ->get();
                endif; 
                if(count($batch_items) > 0):
                    ?>
                    <style>
                    .tabs{width: 100%;}
                    .tabs th,.tabs td {
                      border: 1px solid black;
                      border-collapse: collapse;
                    }
                    .tabs th,.tabs td {
                      padding: 10px;
                    }
                    </style>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group ">
                                <input type="hidden" class="form-control" id="item_id" value="{{$item->id}}"/>
                                <input type="text" class="form-control" id="DeleteTxt"/>
                                <div id="DeleteErr"> </div>
                            </div>
                            
                        </div>
                        <div class="col-md-2 ">
                            <button type="button" class="btn btn-primary pull-right" id="DeleteBtn" style="margin-left: 10px">Submit</button> 
                        </div>
                    </div>
                    <table class="tabs"  >
                        <thead>
                            <tr>
                                <th style="width: 100%"><b>Unique IDs</b></th>
                            </tr> 
                        </thead>
                        <tbody>
                            
                        
                        <tr>
                            <td style="width: 100%">
                                <?php foreach ($batch_items as $key => $value): ?>
                                    <div style="width: 150px;margin: 1px" class="label label-warning">{{$value->unique_id}}</div> 
                                <?php  endforeach; ?>
                                  
                            </td> 
                        </tr> 
                        </tbody>
                    </table>  
                 <?php else: ?>
                    <h6>Sorry, try again later</h6>
                <?php endif; ?>
                
               
            </div>
        </div>
    </div>
</div>