<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/10/25
 * Time: 14:14
 */
namespace app\adminH\controller;
use think\controller\Rest;
use app\adminH\validate;
class Order extends Rest{
    public function index(){
        return $this->fetch("order/index");
    }
    public function order(){
        return $this->fetch("order/order");
    }
    public function model($model){
        return $this->model=model($model);
    }
    
    protected $data=[];
    public function set($data){
        $this->data=$data;
    }
    public function get($data){
        return $this->data;
    }
    /*
     * 添加订单信息，用户下订单获取；
     * */
    public function oadd(){

        $userMobile=input('get.mobile');
        $userId=input('get.id');
        $order['orderno']=input('post.orderno');
        $order['sno']=input('post.sno');   //日程编号
        $order['seatno']=input('post.seatno');   //座位号
        $order['status']=input('post.status');
        $order['seatno']=input('post.seatno');   //座位编号，需要自己去形成；
        $order['startpos']=input('post.startpos');
        $order['endpos']=input('post.endpos');
        $order['carno']=input('post.carno');        //对应的车辆编号
        $order['ondate']=input('post.ondate');      //订车辆的出发时间
        $order['createtime']=date('y-m-d H:i:s',time());
        //验证信息是否合理
        $validate=$this->validate($order,'Order');
        if(true!=$validate){
            return $this->success($validate, $_SERVER['HTTP_REFERER']);
        }else{
            //查询该车辆日程时间是否对,有该车辆的日程信息
            $date=explode(" ",$order['ondate']);
            //$onDate=$date['0'];     //年月日
            $onTime = $date['1'];     //时分秒
            
            //判断用户id(用户登录即可直接获得)
            if(!$userId>'0'){
                return '';
            }

            //判断座位号是否存在，是否是空座；


            //判断该车辆对应的日程是否存在；
            $sno=model('schedule')->where('carno',$order['carno'])->where('timestart','<=',$onTime)->where('timeend','>=',$onTime)->find($order['sno']);


            //订单使用时间是否不在创建时间之后；
            if(strtotime($order['ondate'])<strtotime($order['createtime'])){
                return $this->success('当前时间:'.date('y-m-d H:i:s',time()).',您预订时间：'.$order['ondate'].'已过时，请重新选择',$_SERVER['HTTP_REFERER']);
            }
             
            $result = Db::table('schedule')->where('carno', $order['carno'])->where('starttime', '<=', $onTime)->where('endtime', '>=', $onTime)->find();
            if (true != $result) {
                return $this->success($onTime . '点没有车次，请重新选择', $_SERVER['HTTP_REFERER']);
            } else {
                //订单信息合理
                //将订单状态改为下订单未付款2，
                $order['status']=2;
                $re=model('order')->insert($order);
                if(true!=$re){
                    return false;
                }else{
                    return $this->success('订单添加成功','order/pay');     //支付界面；
                }

            }

        }
    }
    
    
}