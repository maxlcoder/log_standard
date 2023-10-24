<?php

namespace Anchu\LogStandard\Services;

/**
 * 温州市应用日志采集标准接入程序
 * Class Log
 * @package App\ZheService\Logs
 */
class LogFacade
{
    /**
     * @return Log
     */
    public static function getInstance($response = null)
    {
        $type = $_SERVER['log_login_type'] ?? 'xcdn';
        $type = 'Anchu\LogStandard\Services\\' . UcWords($type);
        return new $type($response);
    }
}