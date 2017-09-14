<?php

namespace Api\Controller;

use Think\Controller;

class GalleryController extends Controller
{

    public function index()
    {
        $gallery = M('gallery');

        $limit = I('post.limit');
        if (isset($limit[0]) && isset($limit[1])) {
            $gallery = $gallery->limit((int)$limit[0], (int)$limit[1]);
        }

        if (I('post.order')) {
            $gallery = $gallery->order(I('post.order'));
        }

        $map = [];
        $map['post_status'] = ['eq', 1];
        if (I('post.term_id')) {
            $term = M('gallery_term_relationships')->where("term_id = " . I('post.term_id'))->select();
            $in = [];
            foreach ($term as $v) {
                $in[] = $v['object_id'];
            }
            if (count($in) == 0) {
                echo json_encode([]);
                return;
            }

            $map['id'] = ['in', $in];
        }

        if (I('post.keyword')) {
            $map['post_title'] = ['LIKE', '%'.I('post.keyword').'%'];
        }

        $gallery = $gallery->where($map)->select();

        foreach ($gallery as $k => $v) {
            $gallery[$k]['smeta'] = json_decode($v['smeta'], true);
        }
        echo json_encode($gallery);
    }

    public function terms()
    {
		$term_id = I("post.term_id",0,'intval');
		if ($term_id) {
			 $gallery_terms = M('gallery_terms')->where(array("parent" => array("EQ",$term_id)))->select();
		}else{
			$gallery_terms = M('gallery_terms')->select();
		}
        $gallery_term_relationships = M('gallery_term_relationships')->select();

        echo json_encode(compact('gallery_terms', 'gallery_term_relationships'));
    }

}