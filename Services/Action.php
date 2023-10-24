<?php

namespace Anchu\LogStandard\Services;

/**
 * 用户访问动作详情
 * Class Info
 */
class Action
{
    /**
     * 用户标识
     * 原则上为浙政钉id或浙里办id
     * @example 5630839
     * @var string
     */
    public $userId;

    /**
     * 用户类型
     * 目前限定为群众、企业、政府工作人员、第三方
     * @example 政府工作人员
     * @var string
     */
    public $userRole;

    /**
     * 地区编码
     * 前六位行政区划代码
     * @example 330102
     * @var string
     */
    public $areaCode;

    /**
     * 操作类型
     * 1：登录
     * 2：离开
     * 3：办事开始
     * 4：办事结束
     * 5：进入某功能块
     * @var integer
     */
    public $actionType;

    /**
     * 操作标识
     * $actionType=3/4：
     * actionId用来对某一次办事进行唯一标识，一般为事件的id；
     * $actionType=5：
     * actionId为该功能模块的名称，可以中文表示;
     * @var string
     */
    public $actionId;

    /**
     * 事件类型
     * $actionType=3：
     * 表示对事件类型进行唯一标识，一般为中文
     * @example 申报审批
     * @var string
     */
    public $eventType;

    /**
     * 办事时长
     * $actionType=4：
     * 表示需要记录总办理时长，单位为分钟
     * @example 900
     * @var integer
     */
    public $processingTime;

    /**
     * 操作时间
     * 用户操作发生时间，精确到秒，字段标准格式为：yyyy-MM-ddHH:mm:ss
     * @example 2023-10-23 14:55:34
     * @var string
     */
    public $actionTime;

    /**
     * 操作时长
     * 处理用户操作的时长，即后端接口接受到请求到返回的用时，单位为毫秒
     * @example 838
     * @var integer
     */
    public $actionDuration;

    /**
     * 操作状态
     * 0：成功
     * 1：失败
     * 应用系统处理用户操作的结果状态
     * @example 0
     * @var integer
     */
    public $actionStatus;

    /**
     * 应用编码
     * 应用系统在IRS上注册后的应用编码
     * @example A330000100000202105004179
     * @var string
     */
    public $appCode;
}