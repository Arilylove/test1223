<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/7
 * Time: 17:00
 */
namespace app\adminH\validate;
use think\Validate;
class Car extends Validate
{
    protected $rule=array(
        'cardesc'=>'require|max:8',
        'seatnum'=>'number|between:0,300',
    );
    
    protected $message=array(
        'cardesc.require'=>'车牌号必须',
        'cardesc.max'=>'车牌号不能超过8个字符',
        'seatnum.number'=>'座位数只能为数字',
        'seatnum.between'=>'座位数在0到300之间'
    );
   
}