<?php

namespace elexiangmart\home\controller;

use elexiangmart\common\model\Users as MUsers;
use elexiangmart\common\model\LogSms;

/**
 * ============================================================================
 * 充值控制器
 */
class Addmoney extends Base
{
    public function index()
    {
        return $this->fetch('users/addmoney/index');
    }

    public function logList()
    {
        return $this->fetch('users/addmoney/log_list');
    }
}

