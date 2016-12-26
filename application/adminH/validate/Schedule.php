<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/10
 * Time: 10:10
 */
namespace app\adminH\validate;
use think\Validate;
class Schedule extends Validate{
    protected $rule=[
        'timestart'=>'require|dateFormat:H:i:s',
        'timeend'=>'require|dateFormat:H:i:s',
    ];
    protected $message=[
        'timestart.require'=>'开始时间必须',
        'timestart.dateFormat'=>'开始时间需满足时分秒格式',
        'timeend.require'=>'结束时间必须',
        'timeend.dateFormat'=>'结束时间需满足时分秒格式',
    ];
}