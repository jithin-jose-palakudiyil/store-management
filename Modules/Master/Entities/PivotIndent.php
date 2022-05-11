<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PivotIndent extends Model
{   
    use  SoftDeletes;
    protected $table = "pivot_indent";
    protected $fillable = [];
    protected $guarded = [ ]; 
    protected $dates = ['deleted_at'];
    
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
