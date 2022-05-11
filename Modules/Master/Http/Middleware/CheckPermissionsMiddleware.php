<?php

namespace Modules\Master\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
class CheckPermissionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next,$permission = null)
    {  
        if(\Auth::guard(master_guard)->user()->is_developer==1): return $next($request); endif;
        $GetPermissions = \Modules\Master\Entities\Auth::with('belongsToManyPermissions')->where('id',\Auth::guard(master_guard)->user()->id)->first();
        if($GetPermissions && $GetPermissions->belongsToManyPermissions->isNotEmpty()):
            $permissions = $GetPermissions->belongsToManyPermissions;
            $permission = $permissions->where('slug',$permission)->first(); 
            if($permission): return $next($request);
            else:
                if(\Request::ajax()): 
//                    \Request::session()->flash('flash-error-message','Permission Denied !.');
                    return response()->json(['error','Permission Denied !.'], 200); 
                else:  abort(403,'Permission Denied !.'); endif; 
            endif;
        else: 
            if(\Request::ajax()): 
//                \Request::session()->flash('flash-error-message','Permission Denied !.');
                return response()->json(['error','Permission Denied !.'], 200);
            else: abort(403,'Permission Denied !.');  endif; 
        endif;

    }
}
