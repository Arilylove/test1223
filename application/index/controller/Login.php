<?php
namespace app\index\controller;
use think\Controller;
use org\Verify;
/*
*登录控制器
*/
class Login extends Controller{
    public function index(){
           return $this->fetch('Login/login');
    }

    //后台登录
    
    public function login(){
        $username = input("post.username");
        $password = input("post.password");
        $code = input("post.code");
        //var_dump($username);exit;
        $result = $this->validate(compact('username', 'password', "code"), 'AdminValidate');
        if(true !== $result){
            return json(['code' => -5, 'data' => '', 'msg' => $result]);
        }

        $verify = new Verify();
        if (!$verify->check($code)) {
            return json(['code' => -4, 'data' => '', 'msg' => '验证码错误']);
        }
        $hasUser = db('user')->where('username', $username)->find();
        if(empty($hasUser)){
            return json(['code' => -1, 'data' => '', 'msg' => '管理员不存在']);
        }

        if($password!= $hasUser['password']){
            return json(['code' => -2, 'data' => '', 'msg' => '密码错误']);
        }

        return $this->success('登录成功','index/index');
    }

    //验证码
    public function verify(){
        $verify=new Verify();
        $verify->imageH=32;
        $verify->imageW=120;
        $verify->length=4;
        $verify->useNoise=false;
        $verify->fontSize=16;
        return $verify->entry();
    }

    public function out(){
        unset($_SESSION['adminUser']);
        unset($_SESSION['authority']);
        unset($_SESSION['message']);
        $this->redirect('login/index');
        // $this->success('退出成功',U('login/index'));
    }
}