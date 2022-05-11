<?php

namespace Modules\Master\Http\Controllers\Cron;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\PivotCalibration;
use Modules\Master\Entities\Calibration;
use Exception;
class CalibrationJobController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
       
        //http://localhost/caritas-management/master/calibration-job   - cron job
        //http://localhost/caritas-management/master/calibration-started
        // Initialization
        $redirect = route('cron.calibration-job-started');  
        session_start(); 
        //Redirect to another file that shows that mail queued 
        header("Location: $redirect");

        //Erase the output buffer
        ob_end_clean();

        //Tell the browser that the connection's closed
        header("Connection: close");

        //Ignore the user's abort (which we caused with the redirect).
        ignore_user_abort(true);
        //Extend time limit to 60 minutes
        set_time_limit(3600);
        //Extend memory limit to 10240MB
        ini_set("memory_limit","10240MBM");
        //Start output buffering again
        ob_start();

        //Tell the browser we're serious... there's really
        //nothing else to receive from this page.
        header("Content-Length: 0");

        //Send the output buffer and turn output buffering off.
        ob_end_flush();
        flush();
        //Close the session.
        session_write_close();
    
        $error = null;
        $calibrations = Calibration::with('hasOneCalibrationType:id,days')-> with('hasManyCalibrationDates', function($query) { return $query->orderBy('id', 'DESC')->first(); }) ->where('status',1)->get()->all();
        if (count($calibrations) > 0):   
            try{ 
                foreach ($calibrations as $key => $value) :
                    if($value->hasManyCalibrationDates->first() && $value->hasOneCalibrationType->days):
                        //Initialization of variable
                        $days = $value->hasOneCalibrationType->days; $_last = $value->hasManyCalibrationDates->first(); $difDay = diff_in_days;
                        if($_last): 
                            $lastCalibrationDay =\Carbon\Carbon::createFromFormat('Y-m-d', $_last->date) ; // last Calibration date
                            $nextCalibrationDay= \Carbon\Carbon::createFromFormat('Y-m-d', $_last->date)->addDays($days) ;   // nex Calibration last->date+ calibration-type days
                            $_today = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d')); // today
                            $diffInDays = $nextCalibrationDay->diffInDays($_today); // find dif from today to next Calibration 
                            
                            if($diffInDays == $difDay): // both difDay is equal run the cron
                                if ($lastCalibrationDay->format('Y-m-d') <= $nextCalibrationDay->format('Y-m-d')) :
                                    //Greater than  
                                    $_check = PivotCalibration::where('date',$nextCalibrationDay->format('Y-m-d'))->where('calibration_id',$value->id)->first();
                                    if($_check == null):
//                                        //save the date to the DB
                                        $data = [ 'calibration_id'=>$value->id, 'date'=>$nextCalibrationDay->format('Y-m-d'), 'status'=>0 ];
                                        PivotCalibration::create($data);
                                    endif;   
                                endif;
                            endif;
                              
                        endif;
                    endif;
                endforeach;
            } catch (Exception $ex) { $error = $ex->getMessage(); }     
        endif;
       return response()->json(['error' => $error], 200);
    }

    public function show()
    {
        echo 'Job started';
        
    }
}
