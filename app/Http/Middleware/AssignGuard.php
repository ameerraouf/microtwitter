<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Namshi\JOSE\SimpleJWS;

class AssignGuard extends BaseMiddleware
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($guard != null){
            auth()->shouldUse($guard); //shoud you user guard / table
            $token = $request->header('auth-token');
            $request->headers->set('auth-token', (string) $token, true);
            $request->headers->set('Authorization', 'Bearer '.$token, true);
            try {
                    $secret = config('jwt.secret');
                    $jws = SimpleJWS::load($token);
                    if (!$jws->isValid($secret)) {
                        return  $this -> returnError('401','Unauthenticated user');
                    } 
                    $payload = $jws->getPayload();
            } catch (TokenExpiredException $e) {
                return  $this -> returnError('401','Unauthenticated user');
            } catch (JWTException $e) {
            // dd($e);
                return  $this -> returnError('', 'token_invalid', $e->getMessage());
            }
        }
         return $next($request);
    }
}
