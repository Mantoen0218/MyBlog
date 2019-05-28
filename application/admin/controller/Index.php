<?php


namespace app\admin\controller;


use think\Controller;

class Index extends Controller
{


    function index()
    {

        if (request()->isGet()) {
            return view();
        } else if (request()->isPost()) {

            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('icon');

            $nickname = input('nickname');
            $email = input('email');

            if ($file == null) {

                $res = db('admin')->where('id', session('loginUser')['id'])->update(['nickname' => $nickname, 'email' => $email]);

                if ($res != 0) {

                    $arr = db('admin')->select();

                    session('loginUser', $arr[0]);

                    $this->redirect('admin/Index/index');

                } else{
                    $this->error('你没有修改任何信息！');
                }

            } else {

                // 移动到框架应用根目录/public/uploads/ 目录下
                if ($file) {

                    $info = $file->move(ROOT_PATH . '/public/static/blog/images');

                    if ($info) {

                        $icon = "images/" . $info->getSaveName();

                        $res = db('admin')->where('id', session('loginUser')['id'])->update(['nickname' => $nickname, 'email' => $email, 'icon' => $icon]);

                        if ($res != 0) {

                            $arr = db('admin')->select();

                            session('loginUser', $arr[0]);

                            $this->redirect('admin/Index/index');
                        }

                    } else {
                        // 上传失败获取错误信息
                        echo $file->getError();
                    }
                }

            }

        }

    }

    function password_edit()
    {

        if (request()->isGet()) {
            return view();
        } else if (request()->isPost()) {

            // 原密码
            $before_password = input('beforepassword');

            // 准备修改的密码
            $password = input('password');

            if ($before_password == "" || $password == "") {
                $this->error('密码不能为空！');
            }

            $res = db('admin')->where('password', $before_password)->select();

            if ($res != null) {

                $res = db('admin')->where('id', session('loginUser')['id'])->update(['password' => $password]);

                if ($res) {
                    session('loginUser', null);
                    $this->success('密码修改成功！', 'admin/Home/login');
                } else {
                    $this->error('密码修改失败！');
                }

            } else {
                $this->error('原密码错误！');
            }

        } else {
            $this->error('非法请求！');
        }

    }

}