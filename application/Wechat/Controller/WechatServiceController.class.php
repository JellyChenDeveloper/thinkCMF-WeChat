<?php

namespace Wechat\Controller;

use Common\Controller\MemberbaseController;


class WechatServiceController extends MemberbaseController
{
    public function index()
    {
        $wechat_config = M('wechat_config')->where("authorizer_appid='" . I('get.authorizer_appid') . "'")->find();
        if ($wechat_config && $wechat_config['create_by'] == $this->userid) {
            session('wechat',$wechat_config);
            if ($wechat_config['miniprograminfo']) {
                $this->redirect('/Weapp/Index/index', array('authorizer_appid' => I('get.authorizer_appid')));
            } else {
                $this->redirect('/Mp/Index/index', array('authorizer_appid' => I('get.authorizer_appid')));
            }
        } else {
            $this->error();
        }
    }
}