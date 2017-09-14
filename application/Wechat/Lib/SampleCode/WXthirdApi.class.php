<?php

namespace Wechat\Lib\SampleCode;

class WXthirdApi
{

    public function get_component_verify_ticket()
    {
        $component_verify_ticket = S('component_verify_ticket');
        return $component_verify_ticket;
    }

    public function get_pre_auth_code()
    {
        $pre_auth_code = S('pre_auth_code');

        if ($pre_auth_code) {
            return $pre_auth_code;
        } else {
            $component_access_token = $this->get_component_access_token();

            $WXBizMsgCrypt = new \Wechat\Lib\SampleCode\WXBizMsgCrypt();

            $url = "https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token={$component_access_token}";
            $post_data = [
                "component_appid" => $WXBizMsgCrypt->appId
            ];
            $result = $this->curl($url, $post_data);

            S('pre_auth_code', $result->pre_auth_code, $result->expires_in);
            return $result->pre_auth_code;
        }
    }

    public function get_component_access_token()
    {
        $component_access_token = S('component_access_token');

        if ($component_access_token) {
            return $component_access_token;
        } else {
            $component_verify_ticket = S('component_verify_ticket');

            $WXBizMsgCrypt = new \Wechat\Lib\SampleCode\WXBizMsgCrypt();

            $url = "https://api.weixin.qq.com/cgi-bin/component/api_component_token";
            $post_data = [
                "component_appid" => $WXBizMsgCrypt->appId,
                "component_appsecret" => $WXBizMsgCrypt->AppSecret,
                "component_verify_ticket" => $component_verify_ticket
            ];

            $result = $this->curl($url, $post_data);

            S('component_access_token', $result->component_access_token, $result->expires_in);

            return $result->component_access_token;
        }

    }


    public function api_query_auth($authorization_code)
    {

        $component_access_token = $this->get_component_access_token();

        $url = "https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token={$component_access_token}";

        $WXBizMsgCrypt = new \Wechat\Lib\SampleCode\WXBizMsgCrypt();

        $post_data = [
            "component_appid" => $WXBizMsgCrypt->appId,
            "authorization_code" => $authorization_code
        ];

        $result = $this->curl($url, $post_data);

        return $result;
    }

    public function api_get_authorizer_info($authorizer_appid)
    {

        $component_access_token = $this->get_component_access_token();

        $url = "https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token={$component_access_token}";

        $WXBizMsgCrypt = new \Wechat\Lib\SampleCode\WXBizMsgCrypt();

        $post_data = [
            "component_appid" => $WXBizMsgCrypt->appId,
            "authorizer_appid" => $authorizer_appid
        ];

        $result = $this->curl($url, $post_data);

        return $result;
    }

    public function api_get_authorizer_option($authorizer_appid, $option_name)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/component/ api_get_authorizer_option?component_access_token=xxxx";

        $WXBizMsgCrypt = new \Wechat\Lib\SampleCode\WXBizMsgCrypt();

        $post_data = [
            "component_appid" => $WXBizMsgCrypt->appId,
            "authorizer_appid" => $authorizer_appid,
            "option_name" => $option_name
        ];

        $result = $this->curl($url, $post_data);

        return $result;
    }

    public function curl($url, $post_data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result = json_decode($result);
    }
}