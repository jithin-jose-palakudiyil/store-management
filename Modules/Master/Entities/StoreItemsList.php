<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;
class StoreItemsList extends Model
{
    use SoftDeletes;
    protected $table = "store_items_list";
    protected $fillable = [];
    protected $guarded = [ ]; 
    protected $dates = ['deleted_at'];
 
    // get permissions of user
    public function belongsToManyStoreItems()
    {
        return $this->belongsToMany("Modules\Master\Entities\PivotStoreItems","pivot_store_items",'store_item_id','batch_item_id') ;
    }
    
    /**
     * Must have One Item for Maintenance.
     *
     * @return object
     */
    public function hasOneItem()
    {
        return $this->hasOne(Items::class,  'id','item_id');
    }
    
}
