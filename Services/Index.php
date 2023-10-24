<?php

namespace Anchu\LogStandard\Services;

/**
 * 指标类应用
 * Class Info
 */
class Index
{
    /**
     * 应活跃率
     * 治理侧应用除阶段工作或执法任务外，一般应活跃率为100%
     * @example 100%
     * @var string
     */
    public $activeRate;

    /**
     * 年新增关键业务数据条数
     * @example 5000
     * @var integer
     */
    public $businessData;


    /**
     * 特色业务指标
     * 数组集合，各单位需根据实际业务确定其多个特色业务指标，在此字段中以键值对的形式传递
     * @example [{"key":"表单发布数","value":"1000"}]
     * @var string
     */
    public $ext;

    /**
     * 应用编码
     * 应用系统在IRS上注册后的应用编码
     * @example A330000100000202105004179
     * @var string
     */
    public $appCode;
}