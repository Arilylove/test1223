<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/16
 * Time: 17:07
 */
namespace app\json\validate;
use think\Validate;
class Schedule extends Validate
{
    protected $rule=array(
        'timestart'=>'require|dateFormat:H:i:s',
        'timeend'=>'require|dateFormat:H:i:s',
    );
    protected $message=array(
        'timestart.require'=>'开始时间必须',
        'timestart.dateFormat'=>'开始时间需满足时分秒格式',
        'timeend.require'=>'结束时间必须',
        'timeend.dateFormat'=>'结束时间需满足时分秒格式',
    );

}