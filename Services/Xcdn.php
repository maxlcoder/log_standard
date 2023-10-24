<?php

namespace Anchu\LogStandard\Services;

/**
 * 通过乡村大脑登录后的用户动作日志记录
 * Class Xcdn
 */
class Xcdn extends Log
{
    /**
     * @inheritDoc
     */
    public function setAction()
    {
        // TODO: Implement setUserInfo() method.
        $this->action->userId = $this->user['accountId'] ?? '';
        $this->action->userRole = '政府工作人员';
    }
}