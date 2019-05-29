<?php


namespace app\admin\controller;


use think\Controller;
use app\home\model\Type;
use app\home\model\Article as modelArticle;

class Article extends Controller
{

    function index()
    {

        $article_count = db('article')->count();

        session('article_count', $article_count);

        $type = new Type();
        $type_arr = $type::all();

        $typeId = input('typeId');
        $title = input('title');

        if ($title == null) {
            $title = "";
        }

        $article_arr = [];

        if ($typeId == 0) {
            $article_arr = db('article')->where('title', 'like', '%' . $title . '%')->order('releaseTime desc')->paginate(6,false,['query' => [
                'typeId' => $typeId,
                'title' => $title
            ]]);
        } else {
            $article_arr = db('article')->where('title', 'like', '%' . $title . '%')->where('typeId', $typeId)->order('releaseTime desc')->paginate(6,false,['query' => [
                'typeId' => $typeId,
                'title' => $title
            ]]);
        }

        $this->assign('type_arr', $type_arr);

        $this->assign('title', $title);
        $this->assign('typeId', $typeId);

        $this->assign('article_arr', $article_arr);

        return $this->fetch();

    }

    function add()
    {

        if (request()->isGet()) {

            $type = new Type();

            $arr = $type::all();

            $this->assign('type_arr', $arr);

            return view();

        } else if (request()->isPost()) {

            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('image');

            // 移动到框架应用根目录/public/uploads/ 目录下
            if ($file) {

                $info = $file->move(ROOT_PATH . '/public/static/blog/images');

                if ($info) {

                    $article = new modelArticle();

                    $article->title = input('title');
                    $article->typeId = input('type');
                    $article->introduction = input('introduction');
                    $article->content = input('content');
                    $article->cover = "images/" . $info->getSaveName();
                    $article->releaseTime = date('Y-m-d H:i:s', time());
                    $article->writer = session('loginUser')['nickname'];

                    $article->save();

                    $article_count = db('article')->count();

                    session('article_count', $article_count);

                    $this->redirect('admin/Article/index');

                } else {
                    // 上传失败获取错误信息
                    echo $file->getError();
                }

            }


        } else {
            $this->error('非法请求！');
        }

    }

    function upload_images()
    {

        $files = request()->file();

        $imags = [];

        $errors = [];

        foreach ($files as $file) {

            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . '/public/static/blog/images');

            if ($info) {
                // 成功上传后 获取上传信息
                // 输出 jpg
                //echo $info->getExtension();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                //echo $info->getFilename();

                $path = '/blog/public/static/blog/images/' . $info->getSaveName();
                array_push($imags, $path);

            } else {

                // 上传失败获取错误信息
                //echo $file->getError();
                array_push($errors, $file->getError());

            }
        }
        if (!$errors) {

            $msg['errno'] = 0;
            $msg['data'] = $imags;

            return json($msg);

        } else {

            $msg['errno'] = 1;
            $msg['data'] = $imags;
            $msg['msg'] = "上传出错";

            return json($msg);

        }

    }

    function delete_article()
    {

        $articleId = input('id');

        db('comment')->where('articleId', $articleId)->delete();

        $res = db('article')->where('articleId', $articleId)->delete();

        if ($res) {

            $article_count = db('article')->count();

            session('article_count', $article_count);

            $this->redirect('admin/Article/index');

        } else {
            $this->error('删除失败！');
        }


    }

    function edit_article()
    {

        if (request()->isGet()) {

            $type = new Type();
            $type_arr = $type::all();

            $articleId = input('id');

            $modelarticle = new modelArticle();

            $article = $modelarticle::get($articleId);

            $this->assign('type_arr', $type_arr);
            $this->assign('article', $article);

            return view();

        } else if (request()->isPost()) {

            $article = modelArticle::get(input('articleId'));

            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('image');

            if ($file == null) {

                $article->title = input('title');
                $article->typeId = input('type');
                $article->introduction = input('introduction');
                $article->content = input('content');
                $article->releaseTime = date('Y-m-d', time());
                $article->writer = session('loginUser')['nickname'];

                $article->save();
                $this->redirect('admin/Article/index');

            } else {

                // 移动到框架应用根目录/public/uploads/ 目录下
                if ($file) {

                    $info = $file->move(ROOT_PATH . '/public/static/blog/images');

                    if ($info) {


                        $article->title = input('title');
                        $article->typeId = input('type');
                        $article->introduction = input('introduction');
                        $article->content = input('content');
                        $article->cover = "images/" . $info->getSaveName();
                        $article->releaseTime = Date('Y-m-d H:i:s');
                        $article->writer = session('loginUser')['nickname'];

                        $article->save();

                        $this->redirect('admin/Article/index');

                    } else {
                        // 上传失败获取错误信息
                        echo $file->getError();
                    }

                }

            }


        } else {
            $this->error('非法请求！');
        }


    }

    function comment()
    {

        $articleId = input('articleId');

        $comment_arr = db('comment')->where('articleId', $articleId)->order('commentDate desc')->paginate(6, false, ['query' => [
            'articleId' => $articleId
        ]]);

        $this->assign('comment_arr', $comment_arr);

        return $this->fetch();

    }

    function delete_comment()
    {

        $commentId = input('id');

        $res = db('comment')->where('commentId', $commentId)->delete();

        if ($res) {

            $article_count = db('article')->count();

            session('article_count', $article_count);

            $this->redirect('admin/Article/index');

        } else {
            $this->error('删除失败！');
        }

    }

}