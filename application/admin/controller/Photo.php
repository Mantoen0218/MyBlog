<?php


namespace app\admin\controller;


use think\Controller;

class Photo extends Controller
{

    function index()
    {

        $photo_count = db('photo')->count();

        session('photo_count', $photo_count);

        $photo_arr = db('photo')->order('photoTitle asc')->paginate(6);

        $this->assign('photo_arr', $photo_arr);

        return $this->fetch();
    }

    function add()
    {

        if (request()->isGet()) {
            return view();
        } else if (request()->isPost()) {

            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('photourl');

            $phototitle = input('phototitle');

            if ($file == null || $phototitle == "") {
                $this->error('照片标题与文件不能为空！');
            }


            // 移动到框架应用根目录/public/uploads/ 目录下
            if ($file) {

                $info = $file->move(ROOT_PATH . '/public/static/blog/images');

                if ($info) {

                    $photourl = "images/" . $info->getSaveName();

                    $res = db('photo')->insert(['photoId' => null, 'photoTitle' => $phototitle, 'photoUrl' => $photourl, 'photoTime' => date('Y-m-d', time())]);

                    if ($res != 0) {

                        $photo_count = db('photo')->count();

                        session('photo_count', $photo_count);

                        $this->redirect('admin/Photo/index');
                    }

                } else {
                    // 上传失败获取错误信息
                    echo $file->getError();
                }

            }

        }

    }

    function delete_photo()
    {

        $id = input('id');

        $res = db('photo')->delete($id);

        if ($res) {

            $photo_count = db('photo')->count();

            session('photo_count', $photo_count);

            $this->redirect('admin/Photo/index');


        } else {
            $this->error('删除失败！');
        }

    }

}