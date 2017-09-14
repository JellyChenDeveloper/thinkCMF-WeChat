<?php

namespace V1\Controller;

use Common\Controller\WechatController;

class IndexController extends WechatController
{

    public function index()
    {
        $this->display();
    }

    public function info()
    {
        $info = M('v1_info')->where("appid='{$this->wechat_config['authorizer_appid']}'")->find();
        $info['content'] = htmlspecialchars_decode($info['content']);
        if(!$info['footer']){
            $info['footer']="[]";
        }
        $this->assign('info', $info);
        $this->display();
    }

    public function info_post()
    {
        if (IS_POST) {
            $info = M('v1_info')->where("appid='{$this->wechat_config['authorizer_appid']}'")->find();
            $data = I('post.');
            $data['uid'] = $this->userid;
            $data['appid'] = $this->wechat_config['authorizer_appid'];
            $data['footer'] = json_encode(I('post.footer'));
            if ($info) {
                $data['id'] = $info['id'];
                M('v1_info')->save($data);
            } else {
                M('v1_info')->add($data);
            }
            $this->success('保存成功','info');
        }else{
            $this->error();
        }
    }



}