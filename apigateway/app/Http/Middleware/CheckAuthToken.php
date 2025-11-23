<?php

namespace App\Http\Middleware; 

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CheckAuthToken 
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}