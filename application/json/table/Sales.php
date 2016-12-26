<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/16
 * Time: 11:37
 */
namespace app\json\table;
use think\Db;
class Sales
{
    protected $tableName='sales';

    /*
     * 添加销售量
     * */
    public function addByIndex($carno,$ontime,$seatno){
        //组合索引，显示车辆编号+时间+座位编号；
        $index=sprintf("%07d%010d%03d",$carno,strtotime($ontime),$seatno);
        $data=['index'=>$index];
        $re=Db::table($this->tableName)->insert($data);
        return $re==1?0:1;
    }

    public function delete($carno,$ontime,$seatno){
        $index=sprintf("%07d%010d%03d",$carno,strtotime($ontime),$seatno);
        $re=Db::table($this->tableName)->delete($index);
        return $re==1?0:1;
    }
    
    public function count($carno,$date){
        $minIndex = sprintf("%07d%010d%03d",$carno,strtotime($date),0);
        $maxIndex = sprintf("%07d%010d%03d",$carno,strtotime($date."+1 day"),0);
        return Db::table($this->tableName)->where('index',['>=',$minIndex],['<',$maxIndex],'and')->count();
    }
}