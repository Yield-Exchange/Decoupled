<?php

namespace App\Http\Middleware;

use Closure;

class SecureHeadersMiddleware
{

    // Enumerate headers which you do not want in your application's responses.
    // Great starting point would be to go check out @Scott_Helme's:
    // https://securityheaders.com/
    private $unwantedHeaderList = [
        'X-Powered-By',
        'Server',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // https://gist.github.com/EduardoSP6/221c75332de2dbebebe98bf51f80ddb5
        // https://gist.github.com/chetans9/e07fb1db00caf1f11ee38a9e03514c5c
        $this->removeUnwantedHeaders($this->unwantedHeaderList);
        $response = $next($request);
        try {
            $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('Strict-Transport-Security', 'max-age:31536000; includeSubDomains');
        }catch (\Exception $exception){}
//        $response->headers->set('Content-Security-Policy', "style-src 'self'"); To Be reviewed later
        return $response;
    }

    private function removeUnwantedHeaders($headerList)
    {
        foreach ($headerList as $header)
            header_remove($header);
    }
}