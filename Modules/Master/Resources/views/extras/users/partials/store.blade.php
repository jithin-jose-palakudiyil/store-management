<?php if( count($store) >0 ): ?>
     <label class="display-block text-semibold">Select the sotre</label>
     <div id="store_id_err"></div>
<?php $i=1; foreach ($store as $key => $value) : ?>
    <?php $checked =''; if($request->edit !=null && $request->edit==$value->id): $checked ='checked=""'; endif; ?>
   <?php  if($i==1): ?>
         <div class="row">
    <?php endif;  ?>
            <div class="col-md-4"> 
                <label class="radio-inline">
                    <input type="radio" name="store_id" <?=$checked?> class="styled store_id" value="<?=$value->id?>">
                        <?=$value->name?>
                </label>
            </div>
             <?php  if($i==3): ?>
         </div>
    <?php $i=0; endif; ?>
<?php $i++; endforeach; ?>
<?php endif; ?>