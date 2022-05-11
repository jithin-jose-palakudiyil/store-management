<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
 
class BatchItems extends Model
{
    protected $table = "batch_items";
    protected $fillable = [];
    protected $guarded = [ ]; 
    
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
