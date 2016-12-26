<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/14
 * Time: 17:24
 */
namespace app\json\validate;
use think\Validate;
class User extends Validate{
    protected $rule=array(
        'mobile'=>'require|unique:user',
        'mobile'=>['regex'=>'^(13|14|15|17|18)\d{9}$'],
        'password'=>'require|length:6,20',
        'repassword'=>'confirm:password',
        'sex'=>'require',
        'homeaddr'=>'require',
        'comaddr'=>'require',
        'worktime'=>'require|dateFormat:H:i:s',
        'offtime'=>'require|dateFormat:H:i:s',
    );
    protected $message=array(
        'mobile.require'=>'手机号不能为空',
        'mobile.unique'=>'该手机号已注册过',
        'mobile.regex'=>'请输入正确的手机号',
        'password.require'=>'密码不能为空',
        'password.length'=>'请输入6-20位长度密码',
        'repassword.confirm'=>'请确认两次输入密码相同',
        'sex.require'=>'性别必须',
        'homeaddr.require'=>'家庭住址必须',
        'comaddr.require'=>'公司地址必须',
        'worktime.require'=>'上班时间必须',
        'offtime.require'=>'下班时间必须',
        'worktime.dateFormat'=>'上班时间需满足时分秒格式',
        'offtime.dateFormat'=>'下班时间需满足时分秒格式',
    );
    protected $scene=array(
      'reg'=>'mobile',
    );
}