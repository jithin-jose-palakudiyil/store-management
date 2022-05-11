<?php

namespace Modules\Master\Entities;
 
 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class Auth extends Authenticatable
{
    use SoftDeletes, LogsActivity;
    protected $table = "users";
     
    protected static $logAttributes = ['name','username','password','role','status'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Users';
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
        return "You have {$eventName} a user";
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [];
    protected $guarded = [ ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    protected $dates = ['deleted_at'];
    
    /**
     * Return the Permissions for this Modules.
     *
     * @return array
     */
    public function belongsUserHasPermissions()
    {
        return $this->belongsToMany("Modules\Master\Entities\UserHasPermissions","user_has_permissions","user_id","permission_id") ;
    }
    
    // get permissions of user
    public function belongsToManyPermissions()
    {
        return $this->belongsToMany("Modules\Master\Entities\Permission","user_has_permissions",'user_id','permission_id') ;
    }
}

