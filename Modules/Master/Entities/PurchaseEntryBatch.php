<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model; 

class PurchaseEntryBatch extends Model
{
    protected $table = "purchase_entry_batch";
    protected $fillable = [];
    protected $guarded = [ ]; 
    
    /**
     * Get the comments for the blog post.
     */
    public function hasManyBatchItems()
    {
        return $this->hasMany(BatchItems::class,'batch_id');
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
    
    /**
     * Must have One Item for Maintenance.
     *
     * @return object
     */
    public function hasOnePurchaseEntry()
    {
        return $this->hasOne(PurchaseEntry::class,  'id','purchase_entry_id');
    }
}
