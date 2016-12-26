<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/16
 * Time: 17:34
 */
namespace app\json\validate;
use think\Validate;
class Sale extends Validate
{
    protected $rule=array(
        'carno'=>'require|min:1',
        'date'=>'require|dateFormat:Y-m-d',
    );
    protected $message=array(
        'carno.require'=>'车辆编号必须',
        'carno.min'=>'车辆编号最小为1',
        'date.require'=>'日期必须',
        'date.date'=>'日期需满足日期格式',
    );
}