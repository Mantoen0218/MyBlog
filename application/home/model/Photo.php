<?php


namespace app\home\model;


use think\Model;

class Photo extends Model
{

    protected $photoId = 'photoId';

    //自定义初始化
    protected function initialize()
    {
        // 需要调用`Model`的`initialize`方法
        parent::initialize();
    }

}