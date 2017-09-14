<?php

namespace Weapp\Controller;

use Common\Controller\WeappController;


class IndexController extends WeappController
{


    public function index()
    {
        if($this->wechat_config['auditid']){
            $auditstatus=$this->Weapp->get_latest_auditstatus();
            $this->auditstatus=$auditstatus;
        }
        $qrcode = $this->Weapp->get_qrcode();
        $this->qrcode = $qrcode;
        $this->display();
    }


    public function template()
    {
        $list = M('weapp_template')->select();
        $this->list = $list;
        $this->display();
    }

    public function use_template()
    {
        $template_confi = M('weapp_template')->find(I('get.id'));

        $result = $this->Weapp->template([
            'template_id' => $template_confi['templateid'],
            'ext_json' => json_encode([
                'extAppid' => $this->wechat_config['authorizer_appid'],
                'ext' => [
                    'appid' => $this->wechat_config['authorizer_appid'],
                    'templateid' => $template_confi['templateid']
                ]
            ]),
            'user_version' => 'v1',
            'user_desc' => '',
        ]);

        if ($result['errcode'] == 0) {
            $wechat_config = $this->wechat_config;
            $wechat_config['weapp_theme'] = I('get.id');
            session('wechat', $wechat_config);
            M('wechat_config')->save($wechat_config);
            $this->success('操作成功', U('template'));
        } else {
            $this->error($result['errmsg']);
        }

    }


    function manage_template()
    {
        $template_confi = M('weapp_template')->find($this->wechat_config['weapp_theme']);
        $this->redirect($template_confi['url']);
    }

    public function submit_audit()
    {
        $category = $this->Weapp->get_category();
        if(IS_POST){
            $json=htmlspecialchars_decode(I('json'));
            $data=json_decode($json,true);
            foreach ($category['category_list'] as $c){
                foreach ($data as $k=>$d){
                    if($c['third_class']==$d['first_id']){
                        $data[$k]['first_class']=$c['first_class'];
                        $data[$k]['second_class']=$c['second_class'];
                        $data[$k]['third_class']=$c['third_class'];
                        $data[$k]['first_id']=$c['first_id'];
                        $data[$k]['second_id']=$c['second_id'];
                        $data[$k]['third_id']=$c['third_id'];
                    }else if($c['second_id']==$d['first_id']){
                        $data[$k]['first_class']=$c['first_class'];
                        $data[$k]['second_class']=$c['second_class'];
                        $data[$k]['first_id']=$c['first_id'];
                        $data[$k]['second_id']=$c['second_id'];
                    }else if($c['first_id']==$d['first_id']){
                        $data[$k]['first_class']=$c['first_class'];
                    }
                }
            }
            $result = $this->Weapp->submit_audit($data);
            if($result['errcode']){
                switch ($result['errcode']){
                    case -1:
                        $msg="系统繁忙";
                        break;
                    case 86000:
                        $msg="不是由第三方代小程序进行调用";
                        break;
                    case 86001:
                        $msg="不存在第三方的已经提交的代码";
                        break;
                    case 85006:
                        $msg="标签格式错误";
                        break;
                    case 85007:
                        $msg="页面路径错误";
                        break;
                    case 85008:
                        $msg="类目填写错误";
                        break;
                    case "85009":
                        $msg="已经有正在审核的版本";
                        break;
                    case 85010:
                        $msg="有项目为空";
                        break;
                    case 85011:
                        $msg="标题填写错误";
                        break;
                    case 85023:
                        $msg="审核列表填写的项目数不在1-5以内";
                        break;
                    case 86002:
                        $msg="小程序还未设置昵称、头像、简介。请先设置完后再重新提交。";
                        break;
                }
                $this->error($msg);
            }else{
                M('wechat_config')->save([
                    'id'=>$this->wecaht_config['id'],
                    'auditid'=>$result['auditid'],
                ]);
                $wechat_config = $this->wechat_config;
                $wechat_config['auditid'] = $result['auditid'];
                session('wechat', $wechat_config);
                $this->success('提交成功','index');
            }
            exit;
        }
        $category = $this->Weapp->get_category();
        $page = $this->Weapp->get_page();
        $this->assign([
            'category_list'=> json_encode($category['category_list']),
            'page_list'=> json_encode($page['page_list'])
        ]);
        $this->display();
    }

    public function release(){
        $release = $this->Weapp->release();
        if($release['errcode']==0){
            M('wechat_config')->save([
                'id'=>$this->wechat_config['id'],
                'audit_status'=>3
            ]);
            $this->success('发布成功','index');
        }else{

            $this->error($release['errmsg']);
        }
    }
}