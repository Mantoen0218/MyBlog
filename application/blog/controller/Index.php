<?php


namespace app\blog\controller;


use think\Controller;

class Index extends Controller
{

    function index()
    {

        $type_arr = db('type')->select();

        session('type_arr', $type_arr);

        $article_arr = db('article')->order('releaseTime asc')->paginate(5);

        $this->assign('article_arr', $article_arr);

        return $this->fetch();

    }

    function article_list()
    {

        $typeId = input('typeId');

        $article_arr = [];

        if ($typeId == '0') {
            $article_arr = db('article')->order('releaseTime asc')->paginate(5);
        } else {
            $article_arr = db('article')->where('typeId', $typeId)->order('releaseTime asc')->paginate(5);
        }

        $this->assign('article_arr', $article_arr);

        return $this->fetch();

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

    function article_detail()
    {

        $articleId = input('articleId');

        $article = db('article')->where('articleId', $articleId)->find();

        $this->assign('article', $article);

        return view();

    }

}