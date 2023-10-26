# 温州市应用日志采集规范

## 安装扩展
```shell
composer require hzanchu/log_standard
```

## 在登录接口配置全局变量
```php
// 用户的详情，用于获取用户id
LogFacade::getInstance()->cacheUser($data['token'], $user['data'] ?? []);
// 用户登录类型，可以是乡村大脑登录，也可以是工作台登录（需要额外新增处理类）
$_SERVER['log_login_type'] = 'xcdn';
```

## 配置应用编码
在.env文件中配置：
```shell
// IRS应用编码
LOG_STANDARD_APP_CODE=A33xxxx
// 地区编码，如果没有配置，则根据用户ip获取
LOG_STANDARD_AREA_CODE=330328

// 开启redis
CACHE_DRIVER=redis
REDIS_HOST=xxx
REDIS_PASSWORD=xxx
REDIS_PORT=6379
REDIS_CACHE_DB=21
REDIS_PREFIX=xxx
```

## 配置中间件
`app/Http/Kernel.php`：
```php
protected $middleware = [
    \Anchu\LogStandard\Middlewares\LogStandard::class,
];
```