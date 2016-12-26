<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/17
 * Time: 11:02
 */
namespace app\json\validate;
use think\Validate;
class Seat extends Validate{
    protected $rule=array(
        'carno'=>'require',
        'time'=>'dateFormat:Y-m-d H:i:s',
        'status'=>['regex'=>'\d|\d{1}'],
    );
    protected $message=array(
        'carno.require'=>'车辆编号必须',
        'time.dateFormat'=>'时间需满足YYYY-MM-DD HH:mm:ss日期格式',
        'status.regex'=>'座位状态需类似1|0，2|1，。。',
    );
}