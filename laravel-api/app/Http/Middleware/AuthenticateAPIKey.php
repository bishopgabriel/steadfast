<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\APIKey;

class AuthenticateAPIKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        if (!$apiKey) {
            return response()->json(['status'=>'error', 'message' => 'API key is missing'], 401);
        }

        $isValidApiKey = APIKey::where('key', $apiKey)->exists();

        if (!$isValidApiKey) {
            return response()->json(['status'=>'error', 'message' => 'Invalid API key'], 401);
        }

        return $next($request);
    }
}
