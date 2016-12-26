<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/14
 * Time: 10:35
 */
namespace app\json\validate;
use think\Validate;
class Order extends Validate{
    protected $rule=[
        /*'seatno'=>'number|between:1,50',*/
        'ondate'=>'dateFormat:Y-m-d H:i:s',
        
    ];
    protected $message=[
       /* 'seatno.number'=>'座位号为数字',
        'seatno.between'=>'座位号在1-50之间',*/
        'ondate.dateFormat'=>'出发时间为时间格式Y-m-d H:i:s',
    ];
}