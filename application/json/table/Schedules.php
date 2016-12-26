<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/16
 * Time: 10:55
 */
namespace app\json\table;
use think\Db;
class Schedules{
    protected $tableName='schedule';
    protected $sno;
    protected $carno;
    protected $timestart;
    protected $timeend;
    protected $lid;
    /*
     * 增
     * */
    public function add(){
        $data['sno']=isset($this->sno)?$this->sno:'';
        $data['carno']=isset($this->carno)?$this->carno:'';
        $data['timestart']=isset($this->timestart)?$this->timestart:'';
        $data['timeend']=isset($this->timeend)?$this->timeend:'';
        $data['lid']=isset($this->lid)?$this->lid:'';
        $re=Db::table($this->tableName)->insert($data);
        return $re==1?0:1;
    }
    /*
     * 删
     * */
    public function delete($sno){
        $re=Db::table($this->tableName)->delete($sno);
        return $re==1?0:1;
    }
    /*
     * 改
     * */
    public function update($sno){
        $data['carno']=$this->carno;
        $data['timestart']=$this->timestart;
        $data['timeend']=$this->timeend;
        $data['lid']=$this->lid;
        $re=Db::table($this->tableName)->where('sno',$sno)->update($data);
        return $re==1?0:1;
    }
    /*
     * 查
     * */
    public function find($sno){
        $data=Db::table($this->tableName)->where('sno',$sno)->find();
        if($data){
            $this->carno=$data['carno'];
            $this->sno=$sno;
            $this->timestart=$data['timestart'];
            $this->timeend=$data['timeend'];
            $this->lid=$data['lid'];
            return 0;
        }
        return 1;
    }
    public function findBycarno($carno){
        $data=Db::table($this->tableName)->where('carno',$carno)->find();
        if($data){
            $this->sno=$data['sno'];
            $this->carno=$carno;
            $this->timestart=$data['timestart'];
            $this->timeend=$data['timeend'];
            $this->lid=$data['lid'];
            return 0;
        }
        return 1;
    }
    /*
     * 查询某一时间点的日程
     * */
    public function findByTime($carno,$time){
        $data=Db::table($this->tableName)->where('carno',$carno)->where('timestart','<=',$time)->where('timeend','>=',$time)->find();
        if($data){
            $this->sno=$data['sno'];
            $this->carno=$carno;
            $this->timestart=$data['timestart'];
            $this->timeend=$data['timeend'];
            $this->lid=$data['lid'];
            return 0;
        }
        return 1;
    }
    /*
     * 查询某一线路的日程
     * */
    public function findByLid($lid){
        $data=Db::table($this->tableName)->where('lid',$lid)->find();
        if($data){
            $this->sno=$data['sno'];
            $this->carno=$data['carno'];
            $this->timestart=$data['timestart'];
            $this->timeend=$data['timeend'];
            $this->lid=$lid;
            return 0;
        }
        return 1;
    }



    public function setSno($sno){
        $this->sno=$sno;
    }
    public function getSno(){
        return $this->sno;
    }
    public function setCarno($carno){
        $this->carno=$carno;
    }
    public function getCarno(){
        return $this->carno;
    }
    public function setTimestart($timestart){
        $this->timestart=$timestart;
    }
    public function getTimestart(){
        return $this->timestart;
    }
    public function setTimeend($timeend){
        $this->timeend=$timeend;
    }
    public function getTimeend(){
        return $this->timeend;
    }
    public function setLid($lid){
        $this->lid=$lid;
    }
    public function getLid(){
        return $this->lid;
    }
}