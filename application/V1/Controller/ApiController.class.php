<?php

namespace V1\Controller;

use Think\Controller;

class ApiController extends Controller
{
    public $wechat_config;

    function __construct()
    {
        $this->wechat_config = M('wechat_config')->where("authorizer_appid='" . I('appid') . "'")->find();
        if ($this->wechat_config) {
            return;
        }
    }

    function info()
    {
        $info = M('v1_info')->where("appid='" . I('appid') . "'")->find();
        $info['footer'] = json_decode($info['footer']);
        echo json_encode($info);
    }


    public function gallery()
    {
        $gallery = M('v1_gallery');

        $limit = I('post.limit');
        if (isset($limit[0]) && isset($limit[1])) {
            $gallery = $gallery->limit((int)$limit[0], (int)$limit[1]);
        }

        if (I('post.order')) {
            $gallery = $gallery->order(I('post.order'));
        }

        $map = [];
        $map['wid'] = ['eq', $this->wechat_config['id']];
        $map['post_status'] = ['eq', 1];
        if (I('post.term_id')) {
            $map['term_id'] = ['eq', I('post.term_id')];
        }

        if (I('post.keyword')) {
            $map['post_title'] = ['LIKE', '%' . I('post.keyword') . '%'];
        }
        $gallery = $gallery->where($map)->select();

        foreach ($gallery as $k => $v) {
            $gallery[$k]['smeta'] = json_decode($v['smeta'], true);
        }
        echo json_encode($gallery);
    }

    public function terms()
    {
        $term_id = I("post.term_id", 0, 'intval');
        $map = [];
        $map['wid'] = ['eq', $this->wechat_config['id']];

        if ($term_id) {
            $map['parent'] = array("eq", $term_id);
        }
        $gallery_terms = M('v1_gallery_terms')->where($map)->select();

        echo json_encode($gallery_terms);
    }

    public function slid()
    {
        $amp = [];
        $amp['wid'] = ['eq', $this->wechat_config['id']];
        $slides = M('v1_slide')->where($amp)->select();
        echo json_encode($slides);
    }

}