<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/15
 * Time: 17:46
 */
namespace app\json\table;
use think\Db;
class Userorder{
    protected $tableName='user_order';
    protected $id;
    protected $user;
    protected $order;
    /*
     * 增
     * */
    public function add(){
        $data=[
            'id'=>$this->getId(),
            'user'=>$this->getUser(),
            'order'=>$this->getOrder()
        ];
        $this->id=Db::table($this->tableName)->insertGetId($data);
        $ret=(0!=$this->id)?0:1;
        return $ret;

    }
    /*
     * 删
     * */
    public function delete($id){
       $ret=Db::table($this->tableName)->delete($id);
        return $ret==1?0:1;
    }
    /*
     * 改
     * */
    public function update($id){
        $data=[
            'id'=>$id,
            'user'=>$this->getUser(),
            'order'=>$this->getOrder()
        ];
        $ret=Db::table($this->tableName)->update($data);
        return $ret==1?0:1;
    }
    /*
     * 查
     * */

    public function find($id){
        $data=Db::table($this->tableName)->where("id",$id)->find();
        if($data){
            $this->id=$id;
            $this->user=$data['user'];
            $this->order=$data['order'];
            return 0;
        }
        return 1;
    }

    public function findByUser($id,$user){
        $data=Db::table($this->tableName)->field('id,order')->where('user',$user)->where('id','<',$id)->order('id desc')->limit('10')->select();
        if($data){
            $this->id=$id;
            $this->user=$user;
            $this->order=$data['order'];
            return 0;
        }
        return 1;
    }
    

    public function setId($id){
        $this->id=$id;
    }
    public function getId(){
        return $this->id;
    }
    public function setUser($user){
        $this->user=$user;
    }
    public function getUser(){
        return $this->user;
    }
    public function setOrder($order){
        $this->order=$order;
    }
    public function getOrder(){
        return $this->order;
    }


}
