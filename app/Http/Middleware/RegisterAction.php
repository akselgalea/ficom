<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RegistroService;

class RegisterAction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
      if($request->method() == 'GET') return $next($request);
      
      $response = $next($request);
  
      (new RegistroService)->store($request->path(), $request->method(), $request->all(), $response, Auth()->user());

      return $response;
    } 
}
