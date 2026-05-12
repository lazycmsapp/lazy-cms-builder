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
            'ip_address' => $request->ip(),
            'url' => $request->fullUrl(),
            'referrer' => $request->header('referer'),
            'user_agent' => $userAgent,
            'browser' => $this->getBrowser($userAgent),
            'os' => $this->getOS($userAgent),
            'device_type' => $this->getDeviceType($userAgent),
        ]);
    }

    protected function getBrowser($ua)
    {
        if (preg_match('/MSIE/i', $ua) && !preg_match('/Opera/i', $ua)) return 'IE';
        if (preg_match('/Firefox/i', $ua)) return 'Firefox';
        if (preg_match('/Chrome/i', $ua)) return 'Chrome';
        if (preg_match('/Safari/i', $ua)) return 'Safari';
        if (preg_match('/Opera/i', $ua)) return 'Opera';
        if (preg_match('/Netscape/i', $ua)) return 'Netscape';
        return 'Other';
    }

    protected function getOS($ua)
    {
        if (preg_match('/windows|win32/i', $ua)) return 'Windows';
        if (preg_match('/macintosh|mac os x/i', $ua)) return 'Mac OS';
        if (preg_match('/linux/i', $ua)) return 'Linux';
        if (preg_match('/android/i', $ua)) return 'Android';
        if (preg_match('/iphone|ipad|ipod/i', $ua)) return 'iOS';
        return 'Other';
    }

    protected function getDeviceType($ua)
    {
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $ua)) return 'tablet';
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $ua)) return 'mobile';
        return 'desktop';
    }
}
