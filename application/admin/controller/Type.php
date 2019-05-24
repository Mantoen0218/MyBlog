<?php


namespace app\admin\controller;


use think\Controller;

class Type extends Controller
{

    function index()
    {

        $type_arr = db('type')->paginate(5);

        $this->assign('type_arr', $type_arr);

        return $this->fetch();
    }

    function add()
    {

        if (request()->isGet()) {
            return view();
        } else if (request()->isPost()) {

            $typename = input('typename');

            if ($typename == "") {
                $this->error('类型名称不能为空！');
            }

            $check_typename = db('type')->where('typename', $typename)->find();

            if ($check_typename != null) {
                $this->error('类型名称不能重复！');
            }

            $res = db('type')->insert(['typeid' => null, 'typename' => $typename]);

            if ($res != 0) {

                $type_count = db('type')->count();

                session('type_count', $type_count);

                $this->redirect('admin/Type/index');

            } else {
                $this->error('添加失败！');
            }

        }

    }

    function delete_type()
    {

        $id = input('id');

        $res = db('type')->delete($id);

        if ($res) {

            $type_count = db('type')->count();

            session('type_count', $type_count);

            $this->redirect('admin/Type/index');

        } else {
            $this->error('删除失败！');
        }

    }

}