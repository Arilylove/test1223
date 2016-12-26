<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/14
 * Time: 17:11
 */
namespace app\json\controller;
use think\Request;
use think\File;
use app\json\table\Users;
use app\json\table\Userorder;

class User extends Base{

    public function _initialize()
    {
        parent::_initialize();
        $this->savePath="./upload/logo/";
    }

    /*
     * 手机注册；可以只填写手机号和密码；但如果下订单则需要填写认证信息；
     * */
    public function mobileReg(){
        $mobile=input('param.mobile');
                                         //需要短信验证手机号？
        $validate=$this->validate('mobile','User.reg');
        //var_dump($validate);exit;
        if(true!==$validate){
            $this->setDesc($validate);
            return 2;
        }
        $user=new Users();
        if(0 !=$user->findByMobile($mobile)){
            $regs='0';
            $user->setId('0');
        }else{
            $regs='1';
        }

        $this->setResponseData(['mobile'=>$mobile,'regstatus'=>$regs,'userid'=>$user->getId()]);
        $this->setDesc('注册成功');
        return 0;
        
    }
    public function reg(){
        //return 'ceshi';exit;
        $ret=$this->mobileReg();
        $retdesc=['retcode'=>$ret,'desc'=>$this->getDesc()];
        $data=array_merge($retdesc,$this->getResponseData());
        return $this->response($data,'json',200);
    }

    /*
     * 添加信息（认证信息）
     * */
    public function addUser(){
        $user['mobile']=input('param.mobile');

        $user['nickname']=input('param.nickname');
        $user['password']=input('param.password');
        $user['repassword']=input('param.repassword');
        $user['sex']=input('param.sex');
        $user['homeaddr']=input('param.homeaddr');
        $user['comaddr']=input('param.comaddr');
        $user['worktime']=input('param.worktime');
        $user['offtime']=input('param.offtime');
        //var_dump($user);exit;
        $validate=$this->validate($user,'User');
        //dump($validate);
        if(true===$validate){
            //添加数据库
            $add = new Users();
            //var_dump($add);exit;
            $add->setMobile($user['mobile']);
            $add->setNickname($user['nickname']);
            $add->setSex($user['sex']);
            $add->setPassword($user['password']);
            $add->setHomeaddr($user['homeaddr']);
            $add->setComaddr($user['comaddr']);
            $add->setWorktime($user['worktime']);
            $add->setOfftime($user['offtime']);
            $re = $add->add();
            //var_dump($re);exit;
            if (0 != $re) {
                $this->setDesc("添加数据库失败！");
                return 4;
            }
            $this->setResponseData(['mobile' => $user['mobile'], 'passsword' => md5($user['password']), 'userid' => $add->getId()]);
            $this->setDesc('用户认证成功');
            return 0;

        }else{
            $this->setDesc(" $validate ");
            return 2;
        }

    }
    public function add(){
        $user=$this->addUser();
        $retdesc=['retcode'=>$user,'desc'=>$this->getDesc()];
        $data=array_merge($retdesc,$this->getResponseData());
        return $this->response($data,'json',200);
    }

    /*
     * 修改
     * */
    public function userUpdate(){
        $userid=input('param.id');
        $user['mobile']=input('param.mobile');
        $nickname=input('param.nickname');
        $sex=input('param.sex');
        $user['password']=input('param.password');
        $homeaddr=input('param.homeaddr');
        $comaddr=input('param.comaddr');
        $worktime=input('param.worktime');
        $offtime=input('param.offtime');
        if($userid==''){
            $this->setDesc('请选择需要修改的用户');
            return 2;
        }
        $validate=$this->validate($user,'User');
        if(true!==$validate){
            $this->setDesc("$validate");
            return 2;
        }
            //添加数据库
        $add = new Users();
            //var_dump($add);exit;
        $add->setMobile($user['mobile']);
        $add->setNickname($nickname);
        $add->setSex($sex);
        $add->setPassword($user['password']);
        $add->setHomeaddr($homeaddr);
        $add->setComaddr($comaddr);
        $add->setWorktime($worktime);
        $add->setOfftime($offtime);
        $re = $add->update($userid);
        //var_dump($re);exit;
        if (0 != $re) {
            $this->setDesc("数据修改失败！");
            return 4;
        }
        $this->setResponseData(['mobile' => $user['mobile'], 'passsword' => md5($user['password']), 'userid' => $userid]);
        $this->setDesc('用户信息修改成功');
        return 0;

    }
    public function update(){
        $user=$this->userUpdate();
        $retdesc=['retcode'=>$user,'desc'=>$this->getDesc()];
        $data=array_merge($retdesc,$this->getResponseData());
        return $this->response($data,'json',200);
    }
    /*
     * 头像上传
     * */
    public function logoUpload(){
        $userid=input('param.id');
        if($userid==null){
            $this->setDesc('请选择需要更改头像的用户');
            return 2;
        }
        $file=request()->file('logo');
        var_dump($file);exit;
        $info = $file->move($this->savePath);
        //var_dump($info);
        if($info){
            $logo = $info->getFilename();
            $type = $info->getExtension();           //文件类型
            if ($type == 'jpg' || $type == 'jpeg' || $type == 'png') {
                $user=new Users;
                $user->setLogo($logo);
                /*$add=$user->add();   //添加数据库；
                if(0!=$add){
                    $this->setDesc("头像添加失败");
                    $this->setResponseData([]);
                    return 3;
                }else{
                    $this->setResponseData(['userid'=>$userid,'logo'=>$logo]);
                    $this->setDesc('头像添加成功');
                    return 0;
                }*/
                $update=$user->update($userid);  //修改头像
                if(0!=$update){
                    $this->setDesc('头像上传失败');
                    return 3;
                }else{
                    $this->setResponseData(['userid'=>$userid,'logo'=>$logo]);
                    $this->setDesc('头像上传成功');
                    return 0;
                }
            }else{
                $this->setDesc("请选择类型为jpg或png格式的头像");
                return 2;
            }
        }else{
            $this->setDesc('头像保存失败');
            return 4;
        }

    }
    public function lupload(){
        $result=$this->logoUpload();
        $retdesc=['retcode'=>$result,'desc'=>$this->getDesc()];
        $data=array_merge($retdesc,$this->setResponseData());
        return $this->response($data,'json',200);
    }
    /*
     * 文件上传（同头像上传）
     * */
     public function fileUpload(){
         $userid=input('param.id');
         if($userid==null){
             $this->setDesc('请选择需要上传文件的用户');
             return 2;
         }
         $files=request()->file('logo');
         $info = $files->move($this->savePath);
         //var_dump($info);exit;
         if($info){
             $logo = $info->getFilename();
             $user=new Users;
             $user->setLogo($logo);
             $update=$user->update($userid);
             if(0!=$update){
                 $this->setDesc('文件上传失败');
                 return 3;
             }else{
                 $this->setResponseData(['userid'=>$userid,'logo'=>$logo]);
                 $this->setDesc('文件上传成功');
                 return 0;
             }
         }else{
             $this->setDesc('文件保存失败');
             return 4;
         }
     }
    public function fupload(){
        $result=$this->fileUpload();
        $retdesc=['retcode'=>$result,'desc'=>$this->getDesc()];
        $data=array_merge($retdesc,$this->getResponseData());
        return $this->response($data,'json',200);
    }

    /*
     * 用户信息查询
     * */
    public function userGet(){
        //以用户id为索引获取
        $userid=input('param.id');
        if($userid==null){
            $this->setDesc('请输入需要查找的用户');
            return 2;
        }
        $user=new Users();
        if(0!=$user->find($userid)){
            $this->setDesc('查找失败');
            return 2;
        }
        $data=['userid'=>$userid,'nickname'=>$user->getMobile(),'sex'=>$user->getSex(),'logo'=>$user->getLogo(),'password'=>$user->getPassword(),
        'homeaddr'=>$user->getHomeaddr(),'comaddr'=>$user->getComaddr(),'worktime'=>$user->getWorktime(),'offtime'=>$user->getOfftime()];
        $this->setResponseData($data);
        $this->setDesc("用户 $userid 信息获取成功");
        return 0;
    }
    public function get(){
        $get=$this->userGet();
        $retdesc=['retcode'=>$get,'desc'=>$this->getDesc()];
        $data=array_merge($retdesc,$this->getResponseData());
        return $this->response($data,'json',200);
    }
    /*
     * 订单信息获取
     * */
    public function userOrderGet(){
        $userid=input('param.id');
        $user=input('param.user');
        $orderno=input('param.orderno');
        if($userid==null){
            $this->setDesc('请选择用户');
            return 2;
        }
        //直接查找用户订单；user_order；
        $userOrder=new Userorder();
        if(0!=$userOrder->findByUser($orderno,$userid)){
            $this->setDesc('没有用户订单数据');
            return 3;
        }
        if($orderno==null||$orderno==0){
            $orderno = 0xFFFFFFFF-1;   //?????????????????????????????????
        }

        /*
         * ????????????????????????????????????????????????
         * */
        $ordernoList=$userOrder->getOrder();
        $num=count($ordernoList);
        $retData=array();
        $acnum=0;

        $order=new Order();
        for($i = 0; $i < $num; $i++) {
            $orderNo = $ordernoList[$i]['order'];
            if ( 0 != $order->find($orderNo) ) {
                continue;
            }

            $retData[$acnum]['id'] = $ordernoList[$i]['id'];
            $retData[$acnum]['order'] = $orderNo;
            $retData[$acnum]['carno'] = $order->getCarno();
            $retData[$acnum]['sno'] = $order->getSno();
            $retData[$acnum]['startpos'] = $order->getStartpos();
            $retData[$acnum]['endpos'] = $order->getEndpos();
            $retData[$acnum]['ondate'] = $order->getOndate();

            $Sno = new Schedule();
            if( 0 == $Sno->find($order->getSno()) ) {
                $retData[$acnum]['ontime'] = $Sno->getTimestart();
            }

            $retData[$acnum]['seatno'] = $order->getSeatno();
            $retData[$acnum]['status'] = $order->getStatus();
            $retData[$acnum]['createtime'] = $order->getCreatetime();

            $acnum++;
        }

        $this->setDesc('查询成功');
        $this->setResponseData(['orderlist'=>$retData]);
        return 0;
    }
    public function uorderGet(){
        $get=$this->userOrderGet();
        $retdesc=['retcode'=>$get,'desc'=>$this->getDesc()];
        $data=array_merge($retdesc,$this->getResponseData());
        return $this->response($data,'json',200);
    }

}