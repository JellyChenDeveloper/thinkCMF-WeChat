<?php

namespace Common\Controller;

use Common\Controller\MemberbaseController;

class WechatController extends MemberbaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    function _initialize()
    {
        parent::_initialize();
        $wechat_config = session('wechat');
        if (!$wechat_config || $wechat_config['create_by'] != $this->userid) {
            $this->redirect('/user/WechatService/index');
        }
        if (!$wechat_config['miniprograminfo'] && MODULE_NAME == 'Weapp') {
            $this->redirect('Mp/Index/index', array('authorizer_appid' => I('get.authorizer_appid')));
        }

        if ($wechat_config['miniprograminfo'] && MODULE_NAME == 'Mp') {
            $this->redirect('Weapp/Index/index', array('authorizer_appid' => I('get.authorizer_appid')));
        }

        $this->wechat_config = $wechat_config;
    }

    protected function _listorders($model,$custom_field='') {
        if (!is_object($model)) {
            return false;
        }
        $field=empty($custom_field)&&is_string($custom_field)?'listorder':$custom_field;
        $pk = $model->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data[$field] = $r;
            $model->where(array($pk => $key))->save($data);
        }
        return true;
    }
    
}