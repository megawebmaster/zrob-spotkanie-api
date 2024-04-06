<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next): Response | JsonResponse
    {
        $headers = [
            'Access-Control-Allow-Origin'      => env('CORS_ALLOW_ORIGIN'),
            'Access-Control-Allow-Methods'     => 'POST, GET',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '0',
            'Access-Control-Allow-Headers'     => '*'
        ];

        if ($request->isMethod('OPTIONS'))
        {
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }

        $response = $next($request);
        foreach($headers as $key => $value)
        {
            $response->header($key, $value);
        }

        return $response;
    }
}
