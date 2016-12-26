<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/16
 * Time: 11:35
 */
namespace app\json\table;
use think\Db;
class Seatreal{
    protected $tableName='seat_real_status';
    protected $index;
    protected $status;
    /*
     * 增
     * */
    public function addByIndex($carno,$seatno){
        //日程编号+时间+座位号
        $data['index']=sprintf("%08d%04d",$carno,$seatno);
        $this->setStatus('1');
        $data['status']=$this->getStatus();
        $re=Db::table($this->tableName)->insert($data);
        return $re==1?0:1;
    }

    /*
     * 删
     * */
    public function delete($carno,$seatno){
        $index=sprintf("%08d%04d",$carno,$seatno);
        $re=Db::table($this->tableName)->delete($index);
        return $re==1?0:1;
    }

    /*
     * 改
     * */
    public function updateByStatus($carno,$seatno,$status){
        $index=sprintf("%08d%04d",$carno,$seatno);
        $this->setStatus($status);
        $data=['index'=>$index,'status'=>$this->getStatus()];
        $re=Db::table($this->tableName)->update($data);
        return $re==1?0:1;
    }

    /*
     * 查
     * */
    public function findByIndex($carno,$seatno){
        $index=sprintf("%08d%04d",$carno,$seatno);
        $data=Db::table($this->tableName)->where('index',$index)->find();
        if($data){
            $this->index=$index;
            $this->status=$data['status'];
            return 0;
        }
        return 1;
    }
    /*
     * 没有记录则添加
     * */
    public function queryStatusByIndex($carno,$seatno){
        $index=sprintf("%08d%04d",$carno,$seatno);
        $data=Db::table($this->tableName)->where('index',$index)->find();
        if($data){
            $this->index=$index;
            $this->status=$data['status'];
            return 0;
        }else{
            return $this->addByIndex($carno,$seatno);
        }
        return 1;
    }

    

    public function setIndex($index){
        $this->index=$index;
    }
    public function getIndex(){
        return $this->index;
    }
    public function setStatus($status){
        $this->status=$status;
    }
    public function getStatus(){
        return $this->status;
    }
}