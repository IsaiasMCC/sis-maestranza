<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CountPageVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $route = $request->path(); // o $request->route()->getName() si usas nombres de rutas

        $pageVisit = PageVisit::firstOrCreate(
            ['route' => $route],
            ['visits' => 0]
        );

        $pageVisit->increment('visits');

        return $next($request);
    }
}
