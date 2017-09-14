<?php

namespace Wechat\Controller;

use Common\Controller\HomebaseController;

class IndexController extends HomebaseController
{

    public function index()
    {
    }

    public function reception()
    {
        $server = &load_wechat('Service');
        $data = $server->getComonentTicket();
        switch ($data['InfoType']) {
            case 'unauthorized':
                M('wechat_config')->where("authorizer_appid ='{$data['AuthorizerAppid']}'")->save([
                    'status' => 0,
                    'weapp_theme' => 0
                ]);
                break;
        }
        echo 'success';
        exit;
    }


    public function APPIDreception()
    {
        $wechat = &load_wechat('Receive', I('get.APPID'));

        if ($wechat->valid() === FALSE) {
            // 接口验证错误，记录错误日志
            // log_message('ERROR', "微信被动接口验证失败，{$wechat->errMsg}[{$wechat->errCode}]");
            // 退出程序
            exit($wechat->errMsg);
        }
        log_message('ERROR', "APPIDreception接收，".var_export($wechat,true));
        switch ($wechat->getRev()->getRevType()) {
            // 文本类型处理
            case \Wechat\WechatReceive::MSGTYPE_TEXT:
                $keys = $wechat->getRevContent();
                return $this->_keys($keys);
            // 事件类型处理
            case \Wechat\WechatReceive::MSGTYPE_EVENT:
                $event = $wechat->getRevEvent();
                return $this->_event(strtolower($event['event']));
            // 图片类型处理
//            case \Wechat\WechatReceive::MSGTYPE_IMAGE:
//                return _image();
//            // 发送位置类的处理
//            case \Wechat\WechatReceive::MSGTYPE_LOCATION:
//                return _location();
//            // 其它类型的处理，比如卡卷领取、卡卷转赠
//            default:
//                return _default();
        }
    }

    function _keys($keys)
    {
        $wechat = &load_wechat('Receive',I('get.APPID'));
        // 这里直接原样回复给微信(当然你需要根据业务需求来定制的)
        return $wechat->text($keys)->reply();
    }

    function _event($event)
    {
        $wechat = &load_wechat('Receive',I('get.APPID'));
        switch ($event) {
            case 'weapp_audit_success':
                M('wechat_config')->where(['user_name'=>['eq'=>$wechat->getRevTo()]])->save([
                    'audit_status'=>0
                ]);
//                return $wechat->text('欢迎关注公众号！')->reply();
            case 'weapp_audit_fail':
                M('wechat_config')->where(['user_name'=>['eq'=>$wechat->getRevTo()]])->save([
                    'audit_status'=>1
                ]);
//                return $wechat->text('欢迎关注公众号！')->reply();
            // 粉丝关注事件
            case 'subscribe':
//                return $wechat->text('欢迎关注公众号！')->reply();
            // 粉丝取消关注
            case 'unsubscribe':
                exit("success");
            // 点击微信菜单的链接
            case 'click':
//                return $wechat->text('你点了菜单链接！')->reply();
            // 微信扫码推事件
            case 'scancode_push':
            case 'scancode_waitmsg':
//                $scanInfo = $wechat->getRev()->getRevScanInfo();
//                return $wechat->text("你扫码的内容是:{$scanInfo['ScanResult']}")->reply();
            // 扫码关注公众号事件（一般用来做分销）
            case 'scan':
//                return $wechat->text('欢迎关注公众号！')->reply();
        }
    }


    /*public function APPIDreception() //全网发布检测代码
        {
            $wechat = &load_wechat('Receive', I('get.APPID'));

            if ($wechat->valid() === FALSE) {
                // 接口验证错误，记录错误日志
                // log_message('ERROR', "微信被动接口验证失败，{$wechat->errMsg}[{$wechat->errCode}]");
                // 退出程序
                exit($wechat->errMsg);
            }

    //        file_put_contents('san.txt', var_export($wechat->getRev(), true), FILE_APPEND);
            switch ($wechat->getRev()->getRevType()) {
                // 文本类型处理
                case \Wechat\WechatReceive::MSGTYPE_TEXT:
                    $keys = $wechat->getRevContent();
                    if ($keys == 'TESTCOMPONENT_MSG_TYPE_TEXT') {
                        return $wechat->text('TESTCOMPONENT_MSG_TYPE_TEXT_callback')->reply();
                    } else {
                        $keys2 = explode(':', $keys);
                        if ($keys2[0] == 'QUERY_AUTH_CODE') {
                            echo 'success';
                            $Service = &load_wechat('Service');
                            $AuthorizationInfo = $Service->getAuthorizationInfo($keys2[1]);
                            $wechat->access_token = $AuthorizationInfo['authorizer_access_token'];
                            $data = [
                                'touser' => $wechat->getRevFrom(),
                                'msgtype' => 'text',
                                "text" => ['content' => $keys2[1] . '_from_api']
                            ];
                            $wechat->sendCustomMessage($data);

                            exit;
                        }
                    }
                    return $wechat->text($keys)->reply();
                // 事件类型处理
                case \Wechat\WechatReceive::MSGTYPE_EVENT:
                    $event = $wechat->getRevEvent();
                    return $wechat->text($event['event'] . 'from_callback')->reply();
            }
        }*/
}