<?php

namespace Api\Controller;

use Think\Controller;

class PostsController extends Controller
{

    public function index()
    {
        $posts = M('posts');

        $limit = I('post.limit');
        if (isset($limit[0]) && isset($limit[1])) {
            $posts = $posts->limit((int)$limit[0], (int)$limit[1]);
        }

        if (I('post.order')) {
            $posts = $posts->order(I('post.order'));
        }

        $map['post_status'] = ['eq', 1];
        if (I('post.term_id')) {
            $term = M('term_relationships')->where("term_id = " . I('post.term_id'))->select();
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

        $posts = $posts->where($map)->select();

        foreach ($posts as $k => $v) {
            $posts[$k]['smeta'] = json_decode($v['smeta'], true);
        }

        echo json_encode($posts);
    }

    public function terms()
    {
        $posts_terms = M('terms')->select();
        $posts_terms_relationships = M('term_relationships')->select();

        echo json_encode(compact('posts_terms', 'posts_term_relationships'));
    }


}