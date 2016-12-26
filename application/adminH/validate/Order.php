<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/14
 * Time: 10:35
 */
namespace app\adminH\validate;
use think\Validate;
class Order extends Validate{
    protected $rule=[
        'seatno'=>'number|between:0,50',
        'ondate'=>'dateFormat:y-m-d H:i:s',
        
    ];
    protected $message=[
        'seatno.number'=>'座位号为数字',
        'seatno.between'=>'座位号在0-50之间',
        'ondate.dateFormat'=>'出发时间为时间格式',
    ];
}