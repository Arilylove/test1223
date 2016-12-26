<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/16
 * Time: 11:07
 */
namespace app\json\table;
use think\Db;
class Cars{
    protected $tableName='car';
    protected $carno;
    protected $cardesc;
    protected $seatnum;
    /*
     * 增
     * */
    public function add(){
        $data['carno']=isset($this->carno)?$this->carno:'';
        $data['cardesc']=isset($this->cardesc)?$this->cardesc:'';
        $data['seatnum']=isset($this->seatnum)?$this->seatnum:'';
        $re=Db::table($this->tableName)->insert($data);
        return $re==1?0:1;
    }
    /*
     * 删
     * */
    public function delete($carno){
        $re=Db::table($this->tableName)->delete($carno);
        return $re==1?0:1;
    }
    /*
     * 改
     * */
    public function update($carno){
        $data['cardesc']=$this->cardesc;
        $data['seatnum']=$this->seatnum;
        $re=Db::table($this->tableName)->where('carno',$carno)->update($data);
        return $re==1?0:1;
    }
    /*
     * 查
     * */
    public function find($carno){
        $data=Db::table($this->tableName)->where('carno',$carno)->find();
        if($data){
            $this->cardesc=$data['cardesc'];
            $this->carno=$carno;
            $this->seatnum=$data['seatnum'];
            return 0;
        }
        return 1;
    }
    public function findByDesc($cardesc){
        $data=Db::table($this->tableName)->where('cardesc',$cardesc)->find();
        if($data){
            $this->cardesc=$cardesc;
            $this->carno=$data['carno'];
            $this->seatnum=$data['seatnum'];
            return 0;
        }
        return 1;
    }
    
    
    
    
    public function setCarno($carno){
        $this->carno=$carno;
    }
    public function getCarno(){
        return $this->carno;
    }
    public function setCardesc($cardesc){
        $this->cardesc=$cardesc;
    }
    public function getCardesc(){
        return $this->cardesc;
    }
    public function setSeatnum($seatnum){
        $this->seatnum=$seatnum;
    }
    public function getSeatnum(){
        return $this->seatnum;
    }
}