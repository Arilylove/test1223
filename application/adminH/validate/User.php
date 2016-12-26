<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/8
 * Time: 16:02
 */
namespace app\adminH\validate;
use think\Validate;
class User extends Validate{
    protected $rule=[
        //不能为空验证；
        'mobile'=>'require',
        'mobile'=>'unique:user',//是否唯一
        'mobile'=>['regex'=>'^(13|14|15|17|18)\d{9}$'],
        'nickname'=>'require|unique:user',
        'nickname'=>'length:4,25',
        'password'=>'require|length:6,20',
        'repassword'=>'confirm:password',
        'worktime'=>'require|dateFormat:H:i:s',
        'offtime'=>'require|dateFormat:H:i:s',
    ];
    protected $message=[
        'mobile.require'=>'手机号不能为空',
        'mobile.unique'=>'手机号必须未注册过',
        'mobile.regex'=>'请输入正确的手机号',
        'nickname.require'=>'昵称不能为空',
        'nickname.unique'=>'该昵称已被使用，请重新输入',
        'nickname.length'=>'请输入4-25位长度的昵称',
        'password.require'=>'密码不能为空',
        'password.length'=>'请输入6-20位长度密码',
        'repassword.confirm'=>'请确认两次输入密码相同',
        'worktime.require'=>'上班时间必须',
        'worktime.dateFormat'=>'上班时间需满足时分秒格式',
        'offtime.require'=>'下班时间必须',
        'offtime.dateFormat'=>'下班时间需满足时分秒格式',
    ];
}