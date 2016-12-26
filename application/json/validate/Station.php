<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/12/22
 * Time: 17:40
 */
namespace app\json\validate;
use think\Validate;
class Station extends Validate{
    protected $rule=array(
        'st_name'=>'unique',
    );
    protected $message=array(
        'st_name.unique'=>'该站点已添加'
    );
}