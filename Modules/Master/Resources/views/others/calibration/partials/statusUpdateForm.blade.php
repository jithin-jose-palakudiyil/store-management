<?php if((count($calibration->hasManyCalibrationDates)>0)): ?>
<form id="statusUpdate" action="" name="statusUpdate">
    <input type="hidden" name="calibration_id" id="calibration_id" value="<?=$calibration->id?>" >
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Select Date: <span class="text-danger">*</span></label>
                <select name="date" id="date" data-placeholder="select date" class="select " data-minimum-results-for-search="-1">
                    <option></option> 
                    <?php foreach ($calibration->hasManyCalibrationDates as $key => $value):
                        $status = null;
                        if($value->status==0):
                            $status ='initialized';
                        elseif($value->status==1):
                            $status ='completed';
                        elseif($value->status==2):
//                            $status ='hold';
                        elseif($value->status==3):
                            $status ='rejected';
                        endif;
                        ?>
                    
                    <option value="<?=$value->id?>"><?=$value->date?> <?php echo ($status !=null)? '( '.$status.' )' : ''; ?></option> 
                    <?php endforeach; ?>
                </select>
                <div id="date_err"></div>
            </div>
        </div>  
        <div class="col-md-4">
            <div class="form-group">
                <label>Status: <span class="text-danger">*</span></label>
                <select name="status" id="status" data-placeholder="status" class="select " data-minimum-results-for-search="-1">
                    <option></option> 
                        <option value="1">Completed</option>
                        <!--<option value="2">Hold</option>-->
                        <option value="3">Rejected</option> 
                </select>
                <div id="status_err">
                    @if($errors->has('status'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('status') }}</div>
                    @endif
                </div>
            </div>
        </div>  
        <div class="col-md-4">
                <div class="form-group ">
                    <label for="completion_date">Completion Date <span class="text-danger">*</span></label>
                    <input type="text" readonly="" class="form-control datepicker-menus" id="completion_date" name="completion_date"  placeholder="Completion date" value="" >
                    <div id="completion_date_err"> 
                        @if($errors->has('completion_date'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('completion_date') }}</div>
                        @endif
                    </div>
                </div> 
        </div>
        <div class="col-md-12">
           <div class="form-group ">
               <label for="comments">Comments </label>
               <textarea  class="form-control" id="comments" name="comments" style="resize: none;height: 100px" placeholder="Enter comments"  ></textarea>
            </div> 
       </div>
    </div>
    <div class="row ">
        <div class="col-md-12 ">
            <button type="submit" class="btn btn-primary pull-right"><i class="icon-check"></i> Save</button> 
        </div>
    </div>

</form>
<?php endif; ?>
        