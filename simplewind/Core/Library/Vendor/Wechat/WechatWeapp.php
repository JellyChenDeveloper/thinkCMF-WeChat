<?php

namespace Wechat;

use Wechat\Lib\Common;
use Wechat\Lib\Tools;

/**
 * 微信消息对象解析SDK
 *
 * @author Anyon <380617363@qq.com>
 * @date 2016/06/28 11:29
 */
class WechatWeapp extends Common
{

    /** 消息推送地址 */
    const MODIFY_DOMAIN = '/wxa/modify_domain';
    const TEMPLATE = '/wxa/commit';
    const GET_QRCODE = '/wxa/get_qrcode';
    const SUBMIT_AUDIT = '/wxa/submit_audit';
    const GET_CATEGORY = '/wxa/get_category';
    const GET_PAGE = '/wxa/get_page';


    /**
     * 修改服务器地址
     *
     * @param array action {
     * "action":"add",
     * "requestdomain":["https://www.qq.com","https://www.qq.com"],
     * "wsrequestdomain":["wss://www.qq.com","wss://www.qq.com"],
     * "uploaddomain":["https://www.qq.com","https://www.qq.com"],
     * "downloaddomain":["https://www.qq.com","https://www.qq.com"],
     * }
     * @return array
     */
    public function modify_domain($data)
    {
        empty($this->access_token) && $this->getAccessToken();
        if (empty($this->access_token)) {
            return false;
        }
        $url = self::API_BASE_URL_PREFIX . self::MODIFY_DOMAIN . "?access_token={$this->access_token}";
        $result = Tools::httpPost($url, Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return Tools::log("set modify_domain Faild. {$this->errMsg} [$this->errCode]" . $this->authorizer_appid, 'ERR');
            }
            return true;
        }
        return false;
    }


    /**
     * 修改小程序模版
     *
     * @param array action {
     * "template_id":0,
     * "ext_json":"JSON_STRING", //ext_json需为string类型，请参考下面的格式
     * "user_version":"V1.0",
     * "user_desc":"test",
     * }
     * @return array
     */
    public function template($data)
    {
        empty($this->access_token) && $this->getAccessToken();
        if (empty($this->access_token)) {
            return false;
        }
        $url = self::API_BASE_URL_PREFIX . self::TEMPLATE . "?access_token={$this->access_token}";
        $result = Tools::httpPost($url, Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return Tools::log("set weapp_template Faild. {$this->errMsg} [$this->errCode]" . $this->authorizer_appid, 'ERR');
            }
            return $json;
        }
        return false;
    }


    /**
     * 获取体验小程序的体验二维码
     *
     * @return url
     */
    public function get_qrcode()
    {
        empty($this->access_token) && $this->getAccessToken();
        if (empty($this->access_token)) {
            return false;
        }
        $url = self::API_BASE_URL_PREFIX . self::GET_QRCODE . "?access_token={$this->access_token}";
        $result = Tools::httpGet($url);
        if ($result) {
            $json = json_decode($result, true);
            if ($json['errcode'] == -1) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return Tools::log("set weapp_template Faild. {$this->errMsg} [$this->errCode]" . $this->authorizer_appid, 'ERR');
            }
            return 'data:image/jpeg;base64,' . base64_encode($result);
        }
        return false;
    }

    /**
     * 获取体验小程序的体验二维码
     *
     * @return url
     */
    public function get_category()
    {
        empty($this->access_token) && $this->getAccessToken();
        if (empty($this->access_token)) {
            return false;
        }
        $url = self::API_BASE_URL_PREFIX . self::GET_CATEGORY . "?access_token={$this->access_token}";
        $result = Tools::httpGet($url);
        if ($result) {
            $json = json_decode($result, true);
            if ($json['errcode'] == -1) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return Tools::log("set weapp_template Faild. {$this->errMsg} [$this->errCode]" . $this->authorizer_appid, 'ERR');
            }
//            $category_list=[];
//            foreach($json['category_list'] as $v){
//                if(!$category_list[$v['first_id']]){
//                    $category_list[$v['first_id']]=$v;
//                }
//                $category_list[$v['first_id']]['child'][]=$v;
//            }
//            $category_list2=[];
//            foreach($category_list as $v){
//                $category_list2[]=$v;
//            }
            return $json;
        }
        return false;
    }


    public function get_page()
    {
        empty($this->access_token) && $this->getAccessToken();
        if (empty($this->access_token)) {
            return false;
        }
        $url = self::API_BASE_URL_PREFIX . self::GET_PAGE . "?access_token={$this->access_token}";
        $result = Tools::httpGet($url);
        if ($result) {
            $json = json_decode($result, true);
            if ($json['errcode'] == -1) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return Tools::log("set weapp_template Faild. {$this->errMsg} [$this->errCode]" . $this->authorizer_appid, 'ERR');
            }
            return $json;
        }
        return false;
    }

    public function get_latest_auditstatus()
    {
        empty($this->access_token) && $this->getAccessToken();
        if (empty($this->access_token)) {
            return false;
        }
        $url = self::API_BASE_URL_PREFIX . "/wxa/get_latest_auditstatus?access_token={$this->access_token}";
        $result = Tools::httpGet($url);
        if ($result) {
            $json = json_decode($result, true);
            if ($json['errcode'] == -1) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return Tools::log("set weapp_template Faild. {$this->errMsg} [$this->errCode]" . $this->authorizer_appid, 'ERR');
            }
            return $json;
        }
        return false;
    }

    /**
     * 将第三方提交的代码包提交审核
     *
     * @return url
     */
    public function submit_audit($data)
    {
        empty($this->access_token) && $this->getAccessToken();
        if (empty($this->access_token)) {
            return false;
        }
        $url = self::API_BASE_URL_PREFIX . self::SUBMIT_AUDIT . "?access_token={$this->access_token}";
        $result = Tools::httpPost($url, Tools::json_encode(["item_list"=>$data]));
        if ($result) {
            $json = json_decode($result, true);
            if ($json['errcode'] == -1) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return Tools::log("set weapp_template Faild. {$this->errMsg} [$this->errCode]" . $this->authorizer_appid, 'ERR');
            }
            return $json;
        }
        return false;
    }

    public function release()
    {
        empty($this->access_token) && $this->getAccessToken();
        if (empty($this->access_token)) {
            return false;
        }
        $url = self::API_BASE_URL_PREFIX . "/wxa/release?access_token={$this->access_token}";
        $result = Tools::httpPost($url, Tools::json_encode([]));
        if ($result) {
            $json = json_decode($result, true);
            if ($json['errcode'] == -1) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return Tools::log("set weapp_template Faild. {$this->errMsg} [$this->errCode]" . $this->authorizer_appid, 'ERR');
            }
            return $json;
        }
        return false;
    }
}
