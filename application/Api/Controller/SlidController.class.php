<?php

namespace Api\Controller;

use Think\Controller;

class SlidController extends Controller
{

    public function index()
    {
        $slides = sp_getslide(I('param.idname'));
        echo json_encode($slides);
    }

}