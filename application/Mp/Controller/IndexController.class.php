<?php

namespace Mp\Controller;

use Common\Controller\WechatController;


class IndexController extends WechatController
{

    public function ttt()
    {
        $wechat = &load_wechat('Script', $this->wechat_config['authorizer_appid']);
        $wx_config = $wechat->getJsSign('http://xcx.weihankeji.com/Mp/Index/ttt');
        $wx_config['debug'] = true;


        $wechat = &load_wechat('Oauth', $this->wechat_config['authorizer_appid']);
//        $wechat->();

        $this->wx_config = json_encode($wx_config);
        $this->display();
    }

    public function index()
    {
        $this->display();
    }

    public function menu()
    {
        $wechat = &load_wechat('Menu', $this->wechat_config['authorizer_appid']);
        if (IS_POST) {
            $WeChatMenuArr = json_decode(htmlspecialchars_decode(I('post.WeChatMenuJSON')), true);
            if (count($WeChatMenuArr)) {
                $WeChatMenuInfo = $wechat->createMenu(['button' => $WeChatMenuArr]);
            } else {
                $WeChatMenuInfo = $wechat->deleteMenu();
            }
            echo $WeChatMenuInfo;
            exit;
        }
        $Menu = $wechat->getMenu();
        $this->WeChatMenuJSON = json_encode($Menu ? $Menu['menu']['button'] : array());
        $this->display();
    }


}