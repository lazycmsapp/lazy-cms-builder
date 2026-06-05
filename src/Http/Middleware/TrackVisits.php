<?php

namespace Acme\CmsDashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Acme\CmsDashboard\Models\Analytics;

class TrackVisits
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only track successful GET requests for non-admin pages
        if ($request->isMethod('GET') && $response->getStatusCode() === 200 && !$request->is('admin*') && !$request->is('api*')) {
            $this->logVisit($request);
        }

        return $response;
    }

    protected function logVisit(Request $request)
    {
        $userAgent = $request->header('User-Agent');
        
        Analytics::create([
            'ip_address'  => $request->ip(),
            'url'         => $request->fullUrl(),
            'referrer'    => $request->header('referer'),
            'user_agent'  => $userAgent,
            'browser'     => \Acme\CmsDashboard\Support\UserAgentParser::browser($userAgent),
            'os'          => \Acme\CmsDashboard\Support\UserAgentParser::os($userAgent),
            'device_type' => \Acme\CmsDashboard\Support\UserAgentParser::device($userAgent),
        ]);
    }
}
