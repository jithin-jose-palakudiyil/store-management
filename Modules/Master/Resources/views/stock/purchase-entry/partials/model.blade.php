<div id="modal_theme_primary" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h1 class="modal-title">Add Item</h1>
            </div>
            <div id="Modelerrors"></div>
            <form id="modelItemForm" data-Input="">
                
                <div class="modal-body">
                    <div class="row"> 
                        <div class="col-md-6">
                             <div class="form-group">
                                 <label>Category : <span class="text-danger">*</span></label>
                                 <select name="category_id" id="category_id" data-placeholder="Category" class="select selectM" data-minimum-results-for-search="-1">
                                     <option></option> 
                                     <?php
                                        $ItemCategory = \Modules\Master\Entities\ItemCategory::where('status',1)->get();
                                        foreach ($ItemCategory as $key => $value): ?>
                                             <option  value="{{$value->id}}">{{$value->name}}</option> 
                                             <?php
                                        endforeach;
                                     ?>
                                 </select>
                                <div id="category_id_err"></div>
                             </div>
                         </div>
                        <div class="col-md-6">
                             <div class="form-group">
                                 <label>Measurement Unit : <span class="text-danger">*</span></label>
                                 <select name="measurement_id" id="measurement_id" data-placeholder="Measurement Unit" class="select selectM" data-minimum-results-for-search="-1">
                                     <option></option> 
                                 </select>
                                 <div id="measurement_id_err"> </div>
                             </div>
                         </div> 
                    </div>
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"  placeholder="Enter Name" value="{{(isset($request['value']) && $request['value']) ? $request['value']:''}}" >
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label for="location">Location </label>
                                <input type="text" class="form-control" id="location" name="location"  placeholder="Enter location" value="" >
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Has unique id ? </label>
                                <select name="has_unique_id" id="has_unique_id" data-placeholder="has unique id ?" class="select selectM" data-minimum-results-for-search="-1">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option> 
                                 </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>