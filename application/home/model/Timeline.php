<?php


namespace app\home\model;


use think\Model;

class Timeline extends Model
{

    protected $timeId = 'timeId';

    //自定义初始化
    protected function initialize()
    {
        // 需要调用`Model`的`initialize`方法
        parent::initialize();
    }

}