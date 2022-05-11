<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class Indents extends Model
{
    use LogsActivity, SoftDeletes;
    
    protected $table = "indents";
    protected $fillable = [];
    protected $guarded = [ ]; 
    protected $dates = ['deleted_at'];
    
    protected static $logAttributes = ['date','requested_quantity','request_from','request_to','category_id','item_id','comments','requested_user','authority_user','transferred_user','status'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Indents';
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
        return "You have {$eventName} a indent";
    }
    
    /**
     * Get the comments for the blog post.
     */
    public function hasManyIndentItems()
    {
        return $this->hasMany(PivotIndent::class,'indent_id');
    }
}
