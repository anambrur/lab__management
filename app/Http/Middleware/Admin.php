<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Cache;
class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the migrations table exists
        if (\Schema::hasTable('migrations')) {
            
            
            // if (Auth::guard('admin')->check()) {
            //     dd('Authenticated: ', Auth::guard('admin')->user(), session()->all());
            // } else {
            //     dd('Failed to authenticate',Auth::guard('admin')->check(), session()->all());
                
            // }
            
            // dd([
            //     'session' => session()->all(),
            //     'admin_check' => Auth::guard('admin')->check(),
            //     'admin_user' => Auth::guard('admin')->user(),
            // ]);
            
    
            // Debug Auth::guard('admin')->check()
            if (!Auth::guard('admin')->check()) {
                return redirect()->route('admin.auth.login');
            }
            // Debug Auth::guard('admin')->user()
            $adminUser = Auth::guard('admin')->user();

            // Add online status
            Cache::put('user-' . $adminUser->id, 'online', now()->addMinutes(2));

            // Share settings
            $info = setting('info');
            $whatsapp = setting('whatsapp');
            $api_keys = setting('api_keys');

            view()->share([
                'info' => $info,
                'whatsapp' => $whatsapp,
                'api_keys' => $api_keys
            ]);
        }

        return $next($request);
    }
}
