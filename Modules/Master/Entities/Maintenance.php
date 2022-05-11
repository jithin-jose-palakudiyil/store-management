<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class Maintenance extends Model
{
    use LogsActivity,  SoftDeletes;
    protected $table = "maintenance";
    protected $fillable = [];
    protected $guarded = [ ]; 
    protected $dates = ['deleted_at'];
    
    protected static $logAttributes = ['item_id','maintenance_type_id','company_name','contact_number','contact_email','status'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Maintenance'; 
    
    /**
     * Customizing the LogsActivity.
     *
     * @return array
     */
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->causer_id    = \Auth::guard(master_guard)->user()->id;
        $activity->causer_type  = 'master';
        
    }
    
    /**
     * Customizing the description of LogsActivity.
     *
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} a maintenance";
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
    public function hasOneBatchItem()
    {
        return $this->hasOne(BatchItems::class,  'id','batch_item_id');
    }
    
    /**
     * Must have One Item for Maintenance.
     *
     * @return object
     */
    public function hasOneMaintenanceType()
    {
        return $this->hasOne(MaintenanceType::class,  'id','maintenance_type_id');
    }
    
    /**
     * Must have One Item for Maintenance.
     *
     * @return object
     */
    public function hasManyMaintenanceDates()
    {
        return $this->hasMany(PivotMaintenance::class,  'maintenance_id');
    }
}
