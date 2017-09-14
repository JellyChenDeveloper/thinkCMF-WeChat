<?php

namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class WXthirdController extends AdminbaseController
{

    // 微信第三方配置
    public function index()
    {
        $list = M('wechat_config')->select();
        $this->list = $list;
        $this->display();
    }

    // 微信第三方配置
    public function site()
    {
        $this->WXthird = S('WXthird');
        $this->display();
    }

    public function setting_post()
    {
        S('WXthird', I('post.'));
        $this->success("保存成功！");
    }

    public function weapp_template()
    {
        $list = M('weapp_template')->select();
        $this->list = $list;
        $this->display();
    }

    public function weapp_template_add()
    {
        if (IS_POST) {
            M('weapp_template')->add(I('post.'));
            $this->success("添加成功！");
        }
        $this->display();
    }

    public function weapp_template_delete()
    {
        if (M('weapp_template')->delete(I('get.id'))) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    public function weapp_template_edit()
    {
        if (IS_POST) {
            M('weapp_template')->save(I('post.'));
            $this->success("修改成功！");
        }
        $this->weapp_template = M('weapp_template')->find(I('id'));
        $this->display();
    }


    public function particular()
    {
        $wechat_config = M('wechat_config')->find(I('id'));
        $this->wechat_config=$wechat_config;
        if ($wechat_config['miniprograminfo']) {
            $Weapp=&load_wechat('Weapp', $this->wechat_config['authorizer_appid']);
            if ($this->wechat_config['auditid']) {
                $auditstatus = $Weapp->get_latest_auditstatus();
                $this->auditstatus = $auditstatus;
            }
            $qrcode = $Weapp->get_qrcode();
            $this->qrcode = $qrcode;
        }
        $this->display();
    }

}