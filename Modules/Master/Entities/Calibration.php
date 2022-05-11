<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class Calibration extends Model
{   
    use LogsActivity,  SoftDeletes;
    
    protected $table = "calibration";
    protected $fillable = [];
    protected $guarded = [ ]; 
    protected $dates = ['deleted_at'];
    
    protected static $logAttributes = ['item_id','calibration_type_id','date','last_calibration_date','calibration_by','contact_number','contact_email','status'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Calibration';
//    protected static $submitEmptyLogs = false; 
    
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
        return "You have {$eventName} a calibration";
    }
    
    /**
     * Must have One Item for Calibration.
     *
     * @return object
     */
    public function hasOneItem()
    {
        return $this->hasOne(Items::class,  'id','item_id');
    }
    
    
    /**
     * Must have One Type for Calibration.
     *
     * @return object
     */
    public function hasOneCalibrationType()
    {
        return $this->hasOne(CalibrationType::class,  'id','calibration_type_id');
    }
    
     /**
     * Must have One date for Calibration.
     *
     * @return object
     */
    public function hasManyCalibrationDates()
    {
        return $this->hasMany(PivotCalibration::class,  'calibration_id');
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
    
}
