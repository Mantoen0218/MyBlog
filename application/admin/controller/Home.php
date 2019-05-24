<?php


namespace app\admin\controller;

use think\Controller;

class Home extends Controller
{

    function Test(){}

    function login()
    {

        if (request()->isGet()) {
            return view();
        } else if (request()->isPost()) {

            $username = input('username');
            $password = input('password');

            if ($username == "" || $password == "") {
                $this->error('用户名或密码不能为空！');
                return;
            }

            $arr = db('admin')->where('username', $username)->where('password', $password)->select();

            if ($arr != null) {

                $type_count = db('type')->count();

                session('type_count', $type_count);

                session('loginUser', $arr[0]);
                // $this->success('登陆成功！', 'admin/Home/index');
                $this->redirect('admin/Home/index');

            } else {
                $this->error('用户名或密码错误！');
            }

        } else {
            $this->error('非法请求！');
        }

    }

    function loginout()
    {

        session('loginUser', null);

        $this->redirect('admin/Home/login');

    }

    function index()
    {
        return view();
    }

}