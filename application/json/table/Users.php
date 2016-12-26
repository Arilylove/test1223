<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/14
 * Time: 17:31
 */
namespace app\json\table;
use think\Db;
class Users
{
    protected $tableName='user';
    protected $id;
    protected $nickname;
    protected $mobile;
    protected $logo;
    protected $password;
    protected $sex;
    protected $homeaddr;
    protected $comaddr;
    protected $worktime;
    protected $offtime;
    protected $validate;
    /*
     * 增
     * */
    public function add(){
        //手机号和密码是必要的，其他可以先不填
        $data=[
            'mobile'=>$this->mobile,
            'password'=>$this->password
        ];
        $data['nickname']=isset($this->nickname)?$this->nickname:'';
        $data['sex']=isset($this->sex)?$this->sex:'';
        $data['logo']=isset($this->logo)?$this->logo:'';
        $data['homeaddr']=isset($this->homeaddr)?$this->homeaddr:'';
        $data['comaddr']=isset($this->comaddr)?$this->comaddr:'';
        $data['worktime']=isset($this->worktime)?$this->worktime:'';
        $data['offtime']=isset($this->offtime)?$this->offtime:'';
        //验证信息添加，是否应该都不为空？
        $insert=Db::table($this->tableName)->insert($data);
        if($insert){
            $this->id=Db::table($this->tableName)->field('id')->where('mobile',$data['mobile'])->select();  //获取用户注册id；
            $re=(0!=$this->id)?0:1;
            return $re;
        }else{
            return 1;
        }
    
    }

    /*
     * 删
     * */
    public function delete($id){
        $re=Db::table($this->tableName)->delete($id);
        return $re==1?0:1;
    }

    /*
     * 改
     * */
    public function update($id){
        //用一个参数显示是否有信息改动
        $need=0;
        $data=[
            'id'=>$id
        ];
        $da=Db::table($this->tableName)->where('id',$id)->find();
        if($da==null){
            return 2;
        }
        //var_dump($da);exit;
        if($this->mobile&&strcmp($da['mobile'],$this->mobile)){
            $data['mobile']=$this->mobile;
            $need=1;
        }
        if($this->password&&strcmp($da['password'],$this->password)){
            $data['password']=$this->password;
            $need=1;
        }
        if($this->nickname&&strcmp($da['nickname'],$this->nickname)){
            $data['nickname']=$this->nickname;
            $need=1;
        }
        if($this->sex&&strcmp($da['sex'],$this->sex)){
            $data['sex']=$this->sex();
            $need=1;
        }
        if($this->logo&&strcmp($da['logo'],$this->logo)){
            $data['logo']=$this->logo;
            $need=1;
        }
        if($this->homeaddr&&strcmp($da['homeaddr'],$this->homeaddr)){
            $data['homeaddr']=$this->homeaddr;
            $need=1;
        }
        if($this->comaddr&&strcmp($da['comaddr'],$this->comaddr)){
            $data['comaddr']=$this->comaddr;
            $need=1;
        }
        if($this->worktime&&strcmp($da['worktime'],$this->worktime)){
            $data['worktime']=$this->worktime;
            $need=1;
        }
        if($this->offtime&&strcmp($da['offtime'],$this->offtime)){
            $data['offtime']=$this->offtime;
            $need=1;
        }
        if($need==0){
            return 0;
        }
        $result=Db::table($this->tableName)->update($data);
        return $result==1?0:1;

    }
    /*
     * 头像上传
     * */
 

    public function find($id){
        $data=Db::table($this->tableName)->where('id',$id)->find();
        if($data){
            $this->id=$id;
            $this->mobile=$data['mobile'];
            $this->password=$data['password'];
            $this->nickname=$data['nickname'];
            $this->logo=$data['logo'];
            $this->sex=$data['sex'];
            $this->homeaddr=$data['homeaddr'];
            $this->comaddr=$data['comaddr'];
            $this->worktime=$data['worktime'];
            $this->offtime=$data['offtime'];
            return 0;
        }
        return 1;
    }

    public function findByMobile($mobile){
        $data=Db::table($this->tableName)->where('mobile',$mobile)->find();
        if($data){
            $this->id=$data['id'];
            $this->mobile=$mobile;
            $this->nickname=$data['nickname'];
            $this->logo=$data['logo'];
            $this->sex=$data['sex'];
            $this->homeaddr=$data['homeaddr'];
            $this->comaddr=$data['comaddr'];
            $this->worktime=$data['worktime'];
            $this->offtime=$data['offtime'];
            return 0;
        }
        return 1;
    }


    /**
     * @return mixed
     */
    public function setId($id)
    {
        $this->id=$id;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    public function setMobile($mobile){
        $this->mobile=$mobile;
    }
    public function getMobile(){
        return $this->mobile;
    }
    /**
     * @return mixed
     */
    public function setNickname($nickname)
    {
        $this->nickname=$nickname;
    }
    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }
    public function setPassword($password){
        $this->password=md5($password);
    }
    public function getPassword(){
        return $this->password;
    }
    public function setLogo($logo){
        $this->logo=$logo;
    }
    public function getLogo(){
        return $this->logo;
    }
    public function setSex($sex){
        $this->sex=$sex;
    }
    public function getSex(){
        return $this->sex;
    }
    public function setHomeaddr($homeaddr){
        $this->homeaddr=$homeaddr;
    }

    public function getHomeaddr(){
        return $this->homeaddr;
    }
    public function setComaddr($comaddr){
        $this->comaddr=$comaddr;
    }
    public function getComaddr(){
        return $this->comaddr;
    }
    public function setWorktime($worktime){
        $this->worktime=$worktime;
    }
    public function getWorktime(){
        return $this->worktime;
    }
    public function setOfftime($offtime){
        $this->offtime=$offtime;
    }
    public function getOfftime(){
        return $this->offtime;
    }



}