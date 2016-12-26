<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/8
 * Time: 15:55
 */
namespace app\adminH\controller;
use think\Controller;


class Schedule extends Controller
{
    
    /*
     * 添加车辆日程
     * */
   public function addSc(){
         return $this->fetch('schedule/addSc');
   }

    /*
     * 保存日程信息
     * */
    public function sadd(){
        $this->model=model('schedule');           //需改进
        $sc['sno']=input('post.sno');
        $sc['carno']=input('post.carno');                 //跟车辆信息挂钩
        $sc['timestart']=input('post.timestart');
        $sc['timeend']=input('post.timeend');
        
        //验证
        $validate=$this->validate($sc,'Schedule');
        if(true!=$validate){
            return $this->success($validate,$_SERVER['HTTP_REFERER']);
        }else{
            $result=$this->model->insert($sc);
            if(true!=$result){
                return $this->success('添加失败',$_SERVER['HTTP_REFERER']);
            }else{
                return $this->success('添加成功','Schedule/sList');
            }
        }
    }
  




}