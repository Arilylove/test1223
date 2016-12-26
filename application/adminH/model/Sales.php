<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/14
 * Time: 14:07
 */
namespace app\adminH\model;
use think\Model;
use think\Db;
class Sales extends Model{
  
    protected $tableName='sales';
    //以车辆编号+出发时间+ 表示索引；
    public function addByIndex($carno,$ontime,$seatseq) {
        $index = sprintf("%07d%010d%03d",$carno,strtotime($ontime),$seatseq);
        $data=['index'=>$index];
        $ret = Db::table($this->tableName)->insert($data);
        return $ret==1?0:1;
    }

    public function delByIndex($carno,$ontime,$seatseq) {
        $index = sprintf("%07d%010d%03d",$carno,strtotime($ontime),$seatseq);

        $ret = Db::table($this->tableName)->delete($index);

        return $ret==1?0:1;
    }

    public function countByCarDate($carno,$date) {

        $minIndex = sprintf("%07d%010d%03d",$carno,strtotime($date),0);
        $maxIndex = sprintf("%07d%010d%03d",$carno,strtotime($date."+1 day"),0);
        $salesCount=Db::table($this->tableName)->where('index',['>=',$minIndex],['<',$maxIndex],'and')->count();

        return $salesCount;
    }
}