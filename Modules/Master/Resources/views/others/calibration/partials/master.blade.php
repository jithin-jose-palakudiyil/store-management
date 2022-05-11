<div id="modal_update_div" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php   if(isset($calibration) && $calibration): ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title"><i class="icon-menu7"></i> &nbsp;  <?php echo (isset($calibration->hasOneItem) && $calibration->hasOneItem) ? $calibration->hasOneItem->name.' - '.$calibration->hasOneItem->id : ''; ?></h5>
                </div>
                
                
                <div class="modal-body"> 
                    <div id="MsgDiv"> </div>
                    <div id="FormDiv">
                        @include('master::others.calibration.partials.statusUpdateForm', ['calibration' => $calibration]) 
                    </div> 
                    <hr>
                    <div id="ListDiv">
                        <?php   if(isset($ListModel) && $ListModel): ?>
                        @include('master::others.calibration.partials.DateList', ['ListModel' => $ListModel]) 
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
            <div class="alert alert-danger alert-bordered">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">Oh snap!</span> Change a few things up and try submitting again.
            </div>
            <?php endif; ?>
        </div>
</div> 
</div>