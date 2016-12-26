<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/8
 * Time: 16:02
 */
namespace app\adminH\controller;
use think\Controller;
use app\adminH\model;
use think\Request;

class User extends Controller{
    protected $model;

    /*
     * 用户列表
     * */
    public function index(){
        $this->model=model('user');
        $user=$this->model->select();
        $this->assign('user',$user);
        return $this->fetch('user/index');
    }

    /*
     * 添加用户（用户注册）
     * */
    public function regist(){
        return $this->fetch('user/regist');
    }
    /*
     * 保存用户注册信息
     * */
    public function resave(){
        $this->model=model('user');
        //先上传头像
            //echo ROOT_PATH;exit;
            $file=request()->file('logo');
            var_dump($file);exit;
            if($file) {

                $info=$file->move(ROOT_PATH.'upload'.DS.'logo');
                var_dump($info);exit;
                $user['logo']=$info->getFilename();
                $type=$info->getExtension();           //文件类型
                if($type=='jpg'||$type=='jpeg'||$type=='png'){
                    $user['mobile'] = input('post.mobile');
                    $user['nickname'] = input('post.nickname');
                    $user['sex'] = input('post.sex');
                    $user['homeaddr'] = input('post.homeaddr');
                    $user['comaddr'] = input('post.comaddr');
                    $user['worktime'] =input('post.worktime');
                    $user['offtime'] = input('post.offtime');
                    $user['password'] = input('post.password');
                    $user['repassword'] = input('post.repassword');
                    var_dump($user);exit;
                    $validate=$this->validate($user,'User');
                    //var_dump($validate);exit;
                    if(true!==$validate){
                        return $this->success($validate,$_SERVER['HTTP_REFERER']);
                    }else{
                        $user['password']=md5($user['password']);
                        unset($user['repassword']);
                        $result=$this->model->insert($user);
                        if($result){
                            return $this->success('注册成功','user/index');
                        }else{
                            return $this->success('注册失败',$_SERVER['HTTP_REFERER']);
                        }
                    }
                }else{
                    return $this->success('请上传正确的头像文件',$_SERVER['HTTP_REFERER']);
                }


            }else{
                return $this->success('请上传头像',$_SERVER['HTTP_REFERER']);
            }

        }


    /*
     * 用户下订单信息
     * */
    public function uorder(){
        $uorder['id']=input('post.id');
        $uorder['user']=input('post.user');
        $uorder['order']=input('post.order'); //默认为0；

    }



}