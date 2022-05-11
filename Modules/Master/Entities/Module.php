<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class Module extends Model
{
    use LogsActivity, Sluggable, SoftDeletes;
    protected $table = "module";
    protected $fillable = [];
    protected $guarded = [ ]; 
    protected $dates = ['deleted_at'];
    
    protected static $logAttributes = ['name','slug','is_master','is_store','status'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Module'; 
    
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
        return "You have {$eventName} a module";
    }
    
    
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    
    /**
     * Return the Permissions for this Modules.
     *
     * @return array
     */
    public function hasManyPermissions() {
        return $this->hasMany("Modules\Master\Entities\Permission","module_id","id");
    }
    
}
