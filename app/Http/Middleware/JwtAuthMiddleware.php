<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtAuthMiddleware extends BaseMiddleware
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
        //校验登录信息
        $authResult = $this->authenticate($request);
        if ($authResult['code'] != 200) {
            return response()->json($authResult);
        }
        //设置请求属性
        $request->attributes->add($authResult['data']);

        return $next($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function authenticate(Request $request)
    {
        $result = [
            'code' => 200,
            'msg' => '',
            'data' => []
        ];
        try {
            if (!$this->auth->parser()->setRequest($request)->hasToken()) {
                throw new \Exception( 'Token not provided', '401');
            }
            $user = $this->auth->parseToken()->authenticate();
            if (!$user) {
                throw new \Exception( 'User not found', '401');
            }
            $userInfo = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
            ];
            $result['data'] = $userInfo;
            return $result;
        } catch (\Exception $e) {
            $result['code'] = $e->getCode();
            $result['msg'] = $e->getMessage();
            return $result;
        }
    }

}
