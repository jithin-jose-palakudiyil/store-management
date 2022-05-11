<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
 
class PivotMaintenance extends Model
{  
    protected $table = "pivot_maintenance";
    protected $fillable = [];
    protected $guarded = [ ]; 
    
    
    /**
     * Must have One Item category for Item.
     *
     * @return object
     */
    public function hasOneMaintenance()
    {
        return $this->hasOne(Maintenance::class, 'id','maintenance_id');
    }
    
}
