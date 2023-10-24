<?php

namespace Anchu\LogStandard\Middlewares;

use Anchu\LogStandard\Services\LogFacade;
use Closure;

class LogStandard
{
    /**
     * 处理传入的请求。
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * 在响应发送到浏览器后处理任务。
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     * @return void
     */
    public function terminate($request, $response)
    {
        LogFacade::getInstance($response)->save();
    }
}
