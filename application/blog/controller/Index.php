<?php


namespace app\blog\controller;


use app\home\model\Comment;
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
            $article_arr = db('article')->order('releaseTime asc')->paginate(5, false, ['query' => [
                'typeId' => $typeId
            ]]);
        } else {
            $article_arr = db('article')->where('typeId', $typeId)->order('releaseTime asc')->paginate(5, false, ['query' => [
                'typeId' => $typeId
            ]]);
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

    function timeline()
    {
        return view();
    }

    function article_detail()
    {

        $articleId = input('articleId');

        $comment = db('comment')->where('articleId', $articleId)->order('commentDate asc')->select();

        for ($i = 0; $i < count($comment); $i++) {
            $comment[$i]['avatar'] = $this->getGravatar($comment[$i]['reviewerMail']);
        }

        $article = db('article')->where('articleId', $articleId)->find();

        $prev = db('article')->where('articleId', '<', $articleId)->order('releaseTime asc')->limit(1)->find();//上一篇文章

        $next = db('article')->where('articleId', '>', $articleId)->order('releaseTime asc')->limit(1)->find();//下一篇文章

        $this->assign('prev', $prev);
        $this->assign('next', $next);
        $this->assign('article', $article);
        $this->assign('comment', $comment);

        return view();

    }

    function comment()
    {

        $comment = new Comment();

        $comment->commentDate = date('Y-m-d', time());
        $comment->reviewerName = input('reviewerName');
        $comment->reviewerMail = input('reviewerMail');
        $comment->reviewerSite = input('reviewerSite');
        $comment->reviewContent = input('reviewContent');
        $comment->articleId = input('articleId');

        $comment->save();

        return $this->redirect('blog/Index/article_detail', ['articleId' => input('articleId')]);

    }

    //获取Gravatar头像 QQ邮箱取用qq头像
    function getGravatar($email, $s = 96, $d = 'mp', $r = 'g', $img = false, $atts = array())
    {
        preg_match_all('/((\d)*)@qq.com/', $email, $vai);
        if (empty($vai['1']['0'])) {
            $url = 'https://www.gravatar.com/avatar/';
            $url .= md5(strtolower(trim($email)));
            $url .= "?s=$s&d=$d&r=$r";
            if ($img) {
                $url = '<img src="' . $url . '"';
                foreach ($atts as $key => $val)
                    $url .= ' ' . $key . '="' . $val . '"';
                $url .= ' />';
            }
        } else {
            $url = 'https://q2.qlogo.cn/headimg_dl?dst_uin=' . $vai['1']['0'] . '&spec=100';
        }
        return $url;
    }

}