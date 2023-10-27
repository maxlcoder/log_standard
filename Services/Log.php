<?php

namespace Anchu\LogStandard\Services;

use Illuminate\Support\Facades\Cache;

/**
 * 温州市应用日志采集标准接入程序
 * Class Log
 * @package App\ZheService\Logs
 */
abstract class Log
{
    /**
     * @var Action
     */
    public $action; // 访问动作
    public $index; // 应用指标类日志
    public $user; // 用户的详情

    /**
     * @param \Illuminate\Http\Response $response
     * @return void
     */
    public function __construct($response = null)
    {
        $this->action = new Action();
        $this->action->areaCode = $this->getAreaCode();
        $this->action->actionType = $this->getActionType();
        $this->action->actionId = $this->getActionId();
        $this->action->actionTime = $this->getActionTime();
        $this->action->actionDuration = $this->getActionDuration();
        $this->action->eventType = $this->getEventType();
        $this->action->actionStatus = $this->getActionStatus($response);
        $this->action->appCode = $this->getAppCode();
        $this->getUserInfo();
        $this->setAction();
        $this->getActionType();

        $this->index = new Index();
        $this->getIndex();
    }

    /**
     * 获取用户信息
     */
    protected function getUserInfo()
    {
        $token = request()->bearerToken();
        $this->user = $_SERVER['log_user_info'] ?? json_decode(Cache::get('log_' . $token, '{}'), 1);
    }

    /**
     * 设置用户信息
     * @return Action
     */
    abstract protected function setAction();

    /**
     * 获取行政编码
     * @return string
     */
    protected function getAreaCode()
    {
        if (env('LOG_STANDARD_AREA_CODE', '') != '') {
            return env('LOG_STANDARD_AREA_CODE');
        }
        $ip = request()->ip() == '127.0.0.1' ? '122.224.206.178' : request()->ip();
        $areaCode = Cache::get($ip);
        if (!is_null($areaCode)) return $areaCode;
        // 通过接口返回
        $url = "https://www.qqzeng-ip.com/api/ip?callback=jQuery37101364271321957511_1698045314446&ip=$ip";
        $content = file_get_contents($url);
        $content = substr($content, strpos($content, '{"code"'), -1);
        $content = json_decode($content, 1);
        $areaCode = $content['data']['areacode'] ?? '';
        // 缓存数据
        if (!empty($areaCode)) Cache::put($ip, $areaCode);
        return $areaCode;
    }

    /**
     * 操作类型
     * 1：登录
     * 2：离开
     * 3：办事开始
     * 4：办事结束
     * 5：进入某功能块
     * @var integer
     */
    protected function getActionType()
    {
        $url = request()->url();
        if (strpos($url, 'login') !== false) {
            return 1;
        }
        if (strpos($url, 'logout') !== false) {
            return 2;
        }
        return 5;
    }

    /**
     * 操作标识
     * $actionType=3/4：
     * actionId用来对某一次办事进行唯一标识，一般为事件的id；
     * $actionType=5：
     * actionId为该功能模块的名称，可以中文表示;
     * @var string
     */
    protected function getActionId()
    {
        switch ($this->action->actionType) {
            case 2:
                return 'logout';
            case 3:
            case 4:
            default:
                return request()->getPathInfo();
        }
    }

    /**
     * 事件类型
     * $actionType=3：
     * 表示对事件类型进行唯一标识，一般为中文
     * @example 申报审批
     * @var string
     */
    protected function getEventType()
    {
        return '';
    }

    /**
     * 办事时长
     * $actionType=4：
     * 表示需要记录总办理时长，单位为分钟
     * @example 900
     * @var integer
     */
    protected function getProcessingTime()
    {
        return 0;
    }

    /**
     * 操作时间
     * 用户操作发生时间，精确到秒，字段标准格式为：yyyy-MM-ddHH:mm:ss
     * @example 2023-10-23 14:55:34
     * @var string
     */
    protected function getActionTime()
    {
        return date('Y-m-d H:i:s', LARAVEL_START);
    }

    /**
     * 操作时长
     * @return integer
     */
    protected function getActionDuration()
    {
        return floor((microtime(true) - LARAVEL_START) * 1000);
    }

    /**
     * 操作状态
     * 0：成功
     * 1：失败
     * 应用系统处理用户操作的结果状态
     * @param \Illuminate\Http\Response $response
     * @example 0
     * @var integer
     */
    protected function getActionStatus($response)
    {
        if (!is_null($response)) {
            $content = json_decode($response->getContent(), 1);
            $code = $content['code'] ?? -1;
            if ($code == 0 || $code == 200) {
                return 0;
            }
        }
        return 1;
    }

    /**
     * 应用编码
     * 应用系统在IRS上注册后的应用编码
     * @example A330000100000202105004179
     * @var string
     */
    protected function getAppCode()
    {
        return env('LOG_STANDARD_APP_CODE', '');
    }

    protected function getIndex()
    {
        $this->index->appCode = $this->getAppCode();
        $this->index->activeRate = '100%';
        $this->index->businessData = 0;
        $this->index->ext = '{}';
    }

    public function save()
    {
        // 保存动作日志
        file_put_contents(
            storage_path('logs/00_' . $this->action->appCode . '_log'),
            json_encode($this->action, JSON_UNESCAPED_UNICODE) . PHP_EOL,
            8
        );

        // 保存指标日志
        file_put_contents(
            storage_path('logs/01_' . $this->index->appCode . '_log'),
            json_encode($this->index, JSON_UNESCAPED_UNICODE)
        );
    }

    public function cacheUser($token, $data = [])
    {
        Cache::put('log_' . $token, json_encode($data), 24 * 3600 * 10);
        $_SERVER['log_user_info'] = $data;
    }
}