<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/10
 * Time: 10:40
 */
namespace app\adminH\controller;
use think\controller\Rest;

/*
 * 座位信息；
 * */
class Seat extends Rest{
    /*
     * 已售信息
     * */
    public function sorder(){
        /*
         * seat_order_status 座位订单状态，获取订单状态
         * */
        $this->model=model('seat_order_status');
        $seat['index']=input('post.index');
        //$seat['status']=input('post.status');  
        //$seat['time']=input('post.time');
        //从订单中获取座位订单状态；

        $order=Db::table('order')->where('status','>','0')->select();

        foreach($order as $v){
            $seat['status']=$v['status'];
            $seat['time']=$v['ondate'];
            $insert=$this->model->insert($seat);
            if(true!=$insert){
                return false;
            }
        }

        $seatOrder=$this->model->select();
        $this->assign('seatOrder',$seatOrder);
        return $this->redirect('seat/sorder');
        //return $seatOrder;


        }


    /*
     * 座位实际信息，可作为列表显示；
     * */
    public function sreal(){
        /*
         * seat_real_status
         * */
        $this->model=model('seat_real_status');
        $seat['index']=input('post.index');
        $seat['status']=input('post.status');

        //直接查询状态；
        $seatOrder=Db::table('order')->where('status','>','1')->select();

        foreach($seatOrder as $v){
            $seat['status']=$v['status'];
            $seat['time']=$v['ondate'];
            $insert=$this->model->insert($seat);
            if(true!=$insert){
                return false;
            }
        }
        $sReal=$this->model->select();
        $this->assign('sReal',$sReal);
        return $this->redirect('seat/sReal');
        //return $sReal;

        
    }




}