
<?php
if($LicenceRenewal): 
    $disabled = $display = null;
    if(isset($LicenceRenewal->status) && ($LicenceRenewal->status==1 || $LicenceRenewal->status==3)):
      $disabled = 'disabled=""';  $display ='style="display: none"';
    endif; 
?>
    <form id="statusUpdate" action="" name="statusUpdate">
        <input type="hidden" name="LicenceRenewal_id" id="LicenceRenewal_id" value="<?=$LicenceRenewal->id?>" >
        <div class="row">
            <div class="col-md-12">
                <div class="form-group ">
                    <label>Expiry Date<span class="text-danger">*</span></label>
                    <input disabled=""type="text"  class="form-control "   value="{{(isset($LicenceRenewal->expiry_date) && $LicenceRenewal->expiry_date) ? $LicenceRenewal->expiry_date:''}}" >
                  
                </div> 
            </div>
            <div class="col-md-6">
                <div class="form-group ">
                    <label for="renewed_date">Renewed Date<span class="text-danger">*</span></label>
                    <input <?=$disabled?> type="text" readonly="" class="form-control datepicker-menus" id="renewed_date" name="renewed_date"  placeholder="Renewed Date" value="{{(isset($LicenceRenewal->renewed_date) && $LicenceRenewal->renewed_date) ? $LicenceRenewal->renewed_date:old('renewed_date')}}" >
                    <div id="renewed_date_err"></div>
                </div> 
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Status: <span class="text-danger">*</span></label>
                    <select <?=$disabled?> name="status" id="status" data-placeholder="status" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <option value="1" <?php if( isset($LicenceRenewal->status) && $LicenceRenewal->status==1 && $disabled !=null ): echo'selected="" ';endif; ?> >Completed</option>
                        <option value="2" <?php if( isset($LicenceRenewal->status) && $LicenceRenewal->status==2 && $disabled !=null ): echo'selected="" ';endif; ?> >Hold</option>
                        <option value="3" <?php if( isset($LicenceRenewal->status) && $LicenceRenewal->status==3 && $disabled !=null ): echo'selected="" ';endif; ?> >Rejected</option> 
                    </select>
                    <div id="status_err"></div>
                </div>
            </div>  
            <div class="col-md-12">
               <div class="form-group ">
                   <label for="comments">Comments </label>
                   <textarea <?=$disabled?> class="form-control" id="comments" name="comments" style="resize: none;height: 100px" placeholder="Enter comments"  >{{(isset($LicenceRenewal->comments) && $LicenceRenewal->comments) ? $LicenceRenewal->comments:''}}</textarea>
                </div> 
           </div>
        </div>
        <div class="row " <?=$display?>>
            <div class="col-md-12 ">
                <button type="submit" class="btn btn-primary pull-right"><i class="icon-check"></i> Save</button> 
            </div>
        </div>

    </form>
<?php endif; ?>

        