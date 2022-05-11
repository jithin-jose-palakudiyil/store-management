<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PivotStoreItems extends Model
{   
    use  SoftDeletes;
    protected $table = "pivot_store_items";
    protected $fillable = [];
    protected $guarded = [ ]; 
    
    
    /**
     * Must have One Item category for Item.
     *
     * @return object
     */
    public function hasOneBatch()
    {
        return $this->hasOne(BatchItems::class,  'id','batch_item_id');
    }
}
