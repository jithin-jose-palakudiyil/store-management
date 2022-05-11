<?php
namespace Modules\Master\Helpers;
class Crud
{
    /**
     * Store records to storage 
     * @param object $repository  
     * @param object $request
     * @param string $successMsg
     * @param string $errorMsg 
     * @return response
     */ 
    public static  function store($repository,$request,$successMsg=null,$errorMsg=null,$showMsg=true)
    {
        $response  =   $repository->create($request); 
        if($showMsg): 
            if($response['error'] == null): 
                session()->flash('flash-success-message',$successMsg);
            else: 
                session()->flash('flash-error-message',$errorMsg.'<br/> '.$response['error']);  
            endif;
        endif;
        return $response;
    }
    
    /**
     * Update records to storage 
     * @param object $repository 
     * @param object $record 
     * @param object $request
     * @param string $successMsg
     * @param string $errorMsg 
     * @return response
     */ 
    public static  function update($repository,$record,$request,$successMsg=null,$errorMsg=null)
    {
        $response  =   $repository->update($request,$record);
        if($response['error'] == null):  
            session()->flash('flash-success-message',$successMsg);
        else: 
            session()->flash('flash-error-message',$errorMsg.'<br/> '.$response['error']);  
        endif;
        return $response;
    }
    
    
    /**
     * Update records to storage 
     * @param object $repository 
     * @param object $record 
     * @param string $successMsg
     * @param string $errorMsg 
     * @return response
     */ 
    public static  function destroy($repository,$record,$successMsg=null,$errorMsg=null,$save=null)
    { 
        $msg = [];
        if(\Request::ajax()):  
            $response  =   $repository->delete($record,$save);
            if($response['error'] == null): 
                session()->flash('flash-success-message',$successMsg);
                $msg=array('type'=>'success'); 
            else: 
                session()->flash('flash-error-message',$errorMsg.'<br/> '.$response['error']); 
                $msg=array('type'=>'error');
            endif;
        else:
            \Session::flash('flash-error-message',$this->deleteErrorMessage);
            $msg=array('type'=>'error');
        endif;
        return response()->json($msg, 200);
    }
}