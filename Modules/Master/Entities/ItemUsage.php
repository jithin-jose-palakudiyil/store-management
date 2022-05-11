<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemUsage extends Model
{
    use LogsActivity,   SoftDeletes;
    protected $table = "item_usage";
    protected $fillable = [];
    protected $guarded = [ ];  
    protected $dates = ['deleted_at'];
    
    protected static $logAttributes = ['item_id','usage_date','usage_quantity'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Item Usage';
    
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
        return "You have {$eventName} a item usage";
    }
}
