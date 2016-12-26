<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/16
 * Time: 11:16
 */
namespace app\json\table;
use think\Db;
class Seatorder{
    protected $tableName='seat_order_status';
    protected $index;
    protected $status;
    protected $time;
    /*
     * 增
     * */
    public function add(){
        $data['index']=isset($this->index)?$this->index:'';
        $data['status']=isset($this->status)?$this->status:'';
        $data['time']=isset($this->time)?$this->time:'';
        $re=Db::table($this->tableName)->insert($data);
        return $re==1?0:1;
    }
    public function addByIndex($sno,$time,$seatno){
        //日程编号+时间+座位号
        $data['index']=sprintf("%07d%010d%03d",$sno,strtotime($time),$seatno);
        $this->setStatus('1');
        $data['status']=$this->getStatus();
        $this->setTime(date('Y-m-d H:i:s'));
        $data['time']=$this->getTime();
        $re=Db::table($this->tableName)->insert($data);
        return $re==1?0:1;
    }

    /*
     * 删
     * */
    public function delete($sno,$time,$seatno){
        $index=sprintf("%07d%010d%03d",$sno,strtotime($time),$seatno);
        $re=Db::table($this->tableName)->delete($index);
        return $re==1?0:1;
    }

    /*
     * 改
     * */
    public function update($index){
        $data['status']=$this->status;
        $data['time']=$this->time;
        $re=Db::table($this->tableName)->where('index',$index)->update($data);
        return $re==1?0:1;
    }
    public function updateByStatus($sno,$time,$seatno,$status){
        $index=sprintf("%07d%010d%03d",$sno,strtotime($time),$seatno);
        $this->setStatus($status);
        $this->setTime(date('Y-m-d H:i:s'));
        //var_dump($this->getStatus());exit;
        $data=['index'=>$index,'time'=>$this->getTime(),'status'=>$this->getStatus()];
        //var_dump($data);exit;
        $re=Db::table($this->tableName)->update($data);
        //var_dump($re);exit;
        return $re==1?0:1;
    }

    /*
     * 查
     * */
    public function findByIndex($sno,$time,$seatno){
        $index=sprintf("%07d%010d%03d",$sno,strtotime($time),$seatno);
        $data=Db::table($this->tableName)->where('index',$index)->find();
        if($data){
            $this->index=$index;
            $this->status=$data['status'];
            $this->time=$data['time'];
            return 0;
        }
        return 1;
    }
    /*
     * 查询座位状态，如果记录不存在，则添加，如果状态是2，但是超期，状态改为1，状态是3表示购买成功；
     * */
    public function queryStatusByIndex($sno,$time,$seatno){
        $find=$this->findByIndex($sno,$time,$seatno);
        if($find=='1'){
            $add=$this->addByIndex($sno,$time,$seatno);
            return $add==0?0:1;
        }
        $status=$this->getStatus();
        if($status=='1'){
            return 0;
        }else if($status=='2'){
            $timeNow=date('Y-m-d H:i:s');
            if((strtotime($timeNow)-strtotime($time))>60*30){
                if(0==$this->updateByStatus($sno,$time,$seatno,'1')){
                    return 0;
                }else{
                    return 2;
                }

            }
        }
        return 0;
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
    public function setTime($time){
        $this->time=$time;
    }
    public function getTime(){
        return $this->time;
    }
}