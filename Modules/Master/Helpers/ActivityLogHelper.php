<?php
namespace Modules\Master\Helpers;
use Spatie\Activitylog\Contracts\Activity;
class ActivityLogHelper
{
    /**
     * Store records to storage 
     * @param object $causedBy  
     * @param object $performedOn
     * @param array $withProperties
     * @param string $log_name 
     * @param string $log 
     * @param object $model 
     * @return response
     */ 
    public static  function log($causedBy,$performedOn,$withProperties,$log_name,$log,$model=null)
    {
        activity()->causedBy($causedBy)
        ->performedOn($performedOn) ->withProperties($withProperties) 
        ->tap(function (Activity $activity) use ($log_name,$model)   {
            $activity->log_name=$log_name; 
            $activity->causer_type  = 'master'; 
            if($model!=null){ $activity->subject_id = $model->id;}
            })
        ->log($log);
    }
    
  
}