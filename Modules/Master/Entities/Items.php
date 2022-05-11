<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class Items extends Model
{   
    use LogsActivity,  Sluggable, SoftDeletes;
    
    protected $table = "items";
    protected $fillable = [];
    protected $guarded = [ ]; 
    protected $dates = ['deleted_at'];
    
    protected static $logAttributes = ['name','status','category_id','measurement_id','slug','location'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Item ';
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
        return "You have {$eventName} a item";
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
     * Must have One Item category for Item.
     *
     * @return object
     */
    public function hasOneItemCategory()
    {
        return $this->hasOne(ItemCategory::class,  'id','category_id');
    }
    
    /**
     * Must have One Item category for Item.
     *
     * @return object
     */
    public function hasOneMeasurements()
    {
        return $this->hasOne(Measurements::class,  'id','measurement_id');
    }
    
    /**
     * Get the comments for the blog post.
     */
    public function hasManyStoreItems()
    {
        return $this->hasMany(StoreItemsList::class,  'item_id','id');
    }
    
}
