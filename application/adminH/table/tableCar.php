<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/7
 * Time: 13:48
 */


/*
 * 分层控制器不能直接访问，需要定义完成进行实例化之后调用方法才能访问； car表操作；
 * */
namespace app\adminH\table;
use think\Db;
class tableCar
{
    /*
     * 表参数
     * */

    protected $tableName='car';//表名称
    protected $carno;
    protected $cardesc;
    protected $seatnum;

    /*
     * 测试专用
     * */
    public function test(){
        return "测试";
    }

       /*
        * 添加车辆信息,
        * */
    public function add(){
        $snum=($this->seatnum==0)?35:$this->seatnum; //座位数默认为35；
        $data=['carno'=>$this->carno];
        $result=($this->carno!='')?0:1;
        return $result;
    }




}
