<?php


namespace app\blog\controller;


use think\Controller;

class Index extends Controller
{

    function index()
    {

        $type_arr = db('type')->select();

        session('type_arr', $type_arr);

        return view();

    }

    function article_list()
    {
        return view();
    }

    function photo()
    {

        $photo_arr = db('photo')->order('photoTitle asc')->select();

        $this->assign('photo_arr', $photo_arr);

        return view();

    }

    function Timeline()
    {
        return view();
    }

    function article_search()
    {
        return view();
    }

}