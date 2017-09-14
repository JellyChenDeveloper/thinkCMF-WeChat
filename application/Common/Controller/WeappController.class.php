<?php

namespace Common\Controller;

use Common\Controller\WechatController;

class WeappController extends WechatController
{

    public function __construct()
    {
        parent::__construct();
    }


    function _initialize()
    {
        parent::_initialize();
        $Weapp = &load_wechat('Weapp', $this->wechat_config['authorizer_appid']);
        $this->Weapp=$Weapp;
    }

}