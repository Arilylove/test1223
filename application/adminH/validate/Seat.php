<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/11
 * Time: 14:05
 */
namespace app\adminH\validate;
use think\Validate;
class Seat extends Validate{
    protected $rule=[
        'time'=>'require',
        'time'=>'dateFormat:Y-m-d H:i:s',
    ];
    protected $message=[
        'time.require'=>'时间必须',
        'time.dateFormat'=>'时间需要满足年-月-日 时：分：秒格式',
    ];

}