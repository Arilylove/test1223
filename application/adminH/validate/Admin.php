<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/10/24
 * Time: 16:38
 */
namespace app\adminH\validate;
use think\Validate;

class AdminValidate extends Validate{
   protected $rules=[
       ['username','require','用户名不能为空'],
       ['password','require','密码不能为空'],
       ['code','require','验证码不能为空']
   ];
}
