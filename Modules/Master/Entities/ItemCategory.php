<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class ItemCategory extends Model
{   
    use LogsActivity,  Sluggable, SoftDeletes;
    
    protected $table = "item_category";
    protected $fillable = [];
    protected $guarded = [ ]; 
    protected $dates = ['deleted_at'];
    
    protected static $logAttributes = ['name','measurement_id','status','allow_usage','slug'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Item Category';
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
        return "You have {$eventName} a item category";
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
     * Return the Item Category Unit for this Modules.
     *
     * @return array
     */
    public function belongsToMeasurements()
    {
        return $this->belongsToMany("Modules\Master\Entities\ItemCategoryUnit","item_category_unit","category_id","measurement_id") ;
    }
    
    // get Measurements of category
    public function belongsToManyMeasurements()
    {
        return $this->belongsToMany("Modules\Master\Entities\Measurements","item_category_unit",'category_id','measurement_id') ;
    }
    
     
    
}
