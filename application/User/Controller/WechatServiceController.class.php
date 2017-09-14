<?php

namespace User\Controller;

use Common\Controller\MemberbaseController;

use Wechat\Lib;

class WechatServiceController extends MemberbaseController
{

    public function index()
    {
        $mp['create_by'] = ['eq', $this->userid];
        $mp['status'] = ['eq', 1];
        $list = M('wechat_config')->where($mp)->select();
        $this->list = $list;
        $this->display();
    }


    public function add()
    {
        $server = &load_wechat('Service');
        $url = $server->getAuthRedirect("http://{$_SERVER['HTTP_HOST']}/User/WechatService/reception");
        echo "<script>window.location.href='{$url}';</script>";
    }

    public function reception()
    {
        if ($this->userid > 0) {

        } else {
            file_put_contents('error.txt', "\n" . date('Y-m-d H:i:s') . '小程序授权成功,但用户未登录', FILE_APPEND);
            return;
        }
        $server = &load_wechat('Service');
        $AuthorizationInfo = $server->getAuthorizationInfo(I('get.auth_code'));

        $WechatInfo = $server->getWechatInfo($AuthorizationInfo['authorizer_appid']);

        $data = array(
            'authorizer_appid' => $AuthorizationInfo['authorizer_appid'],
            'authorizer_access_token' => $AuthorizationInfo['authorizer_access_token'],
            'authorizer_refresh_token' => $AuthorizationInfo['authorizer_refresh_token'],
            'func_info' => $AuthorizationInfo['func_info'],
            'nick_name' => $WechatInfo['nick_name'],
            'head_img' => $WechatInfo['head_img'],
            'expires_in' => $AuthorizationInfo['expires_in'],
            'service_type' => $WechatInfo['service_type'],
            'service_type_info' => $WechatInfo['service_type_info'],
            'verify_type_info' => $WechatInfo['verify_type_info'],
            'user_name' => $WechatInfo['user_name'],
            'alias' => $WechatInfo['alias'],
            'qrcode_url' => $WechatInfo['qrcode_url'],
            'business_info' => $WechatInfo['business_info'],
            'idc' => 0,
            'status' => 1,
            'create_by' => $this->userid,
            'principal_name' => $WechatInfo['principal_name'],
            'signature' => $WechatInfo['signature'],
            'miniprograminfo' => $WechatInfo['MiniProgramInfo'] ? 1 : 0,
            'create_at' => date('Y-m-d H:i:s')
        );

        switch ($WechatInfo['verify_type_info']) {
            case -1:
                $data['verify_type'] = '未认证';
                break;
            case 0:
                $data['verify_type'] = '微信认证';
                break;
            case 1:
                $data['verify_type'] = '新浪微博认证';
                break;
            case 2:
                $data['verify_type'] = '腾讯微博认证';
                break;
            case 3:
                $data['verify_type'] = '已资质认证通过但还未通过名称认证';
                break;
            case 4:
                $data['verify_type'] = '已资质认证通过、还未通过名称认证，但通过了新浪微博认证';
                break;
            case 5:
                $data['verify_type'] = '已资质认证通过、还未通过名称认证，但通过了腾讯微博认证';
                break;
        }
        if ($WechatInfo['MiniProgramInfo']) {
            $data['type'] = '小程序';
        } else {
            if ($WechatInfo['service_type_info'] == 2) {
                $data['type'] = '服务号';
            } else {
                $data['type'] = '订阅号';
            }
        }

        $do = M('wechat_config')->field('id,create_at')->where("authorizer_appid='{$data['authorizer_appid']}'")->find();

        if ($do) {
            $data['id'] = $do['id'];
            M('wechat_config')->save($data);
        } else {
            M('wechat_config')->add($data);
        }

        if ($WechatInfo['MiniProgramInfo']) {
            $Weapp = &load_wechat('Weapp', $data['authorizer_appid']);
            $Weapp->modify_domain([
                'action' => 'set',
                'requestdomain' => $_SERVER['HTTP_HOST'],
                'wsrequestdomain' => $_SERVER['HTTP_HOST'],
                'uploaddomain' => $_SERVER['HTTP_HOST'],
                'downloaddomain' => $_SERVER['HTTP_HOST'],
            ]);
        }
        $this->success('创建成功', 'index');
    }

}