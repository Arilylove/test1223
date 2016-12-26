<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/7
 * Time: 14:21
 */
namespace app\adminH\controller;
use app\adminH\table\tableCar;
use think\Controller;
use think\Db;
use think\Loader;

class Car extends Controller
{
    /* 测试专用
     * */
    public function test()
    {
        $test=new tableCar();   //通过分层控制器调用table层中方法；
        return $test->test();
    }
    
     /*
      * 添加车辆信息，
      * */
     public function addCar(){

         return $this->fetch('car/addCar');

     }

    /*
     * 保存添加的车辆信息
     * */
    public function saveAdd(){
        $car['cardesc']=input('post.cardesc'); //车牌号
        $car['seatnum']=input('post.seatnum'); //座位数
        
       ///dump($car);exit;

        $validate=$this->validate($car,'Car');
        //var_dump($vali);exit;
        if(true!==$validate){
           return $this->success($validate,$_SERVER['HTTP_REFERER']);
        }
        $cdesc=Db::table('car')->where('cardesc',$car['cardesc'])->find();
        //dump($cdesc);exit;
        if($cdesc!=''){
            return $this->success('该车辆已添加，无需重复添加',$_SERVER['HTTP_REFERER']);
        }
        $res=Db::table('car')->insert($car);
        //dump($res);exit;
        if($res==''){
            return $this->success('添加失败',$_SERVER['HTTP_REFERER']);
        }else{
            return $this->success('添加'.$res.'条车辆信息成功','car/carList');  //成功输出1，表示添加了一条信息；
        }
    }
    /*
     * 车辆列表
     * */
    public function carList(){
        $data=Db::table('car')->order('carno desc')->select();
        //dump($data);exit;
        $this->assign('data',$data);
        return $this->fetch('car/carList');
    }


    public function edit(){
        $car['carno']=input('param.carno');
        $car=Db::table('car')->where('carno',$car['carno'])->find();
        //var_dump($car);exit;
        $this->assign('car',$car);
        return $this->fetch('car/edit');
    }
    /*
     * 保存车辆修改信息
     * */
    public function editsave(){
        $car['carno']=input('param.carno');
        $car['cardesc']=input('param.cardesc'); //车牌号
        $car['seatnum']=input('param.seatnum'); //座位数
        //dump($car);exit;
        $validate=$this->validate($car,'Car');
        if(true!==$validate){
            return $this->success($validate,$_SERVER['HTTP_REFERER']);
        }
        if($car['carno']>0){
            $res=Db::table('car')->where('carno',$car['carno'])->update($car);
        }else{
            $res=Db::table('car')->data($car)->save();
        }
        if($res!=false){
            return $this->success('车辆信息修改成功','car/carList');
        }
        
    }
    /*
     * 删除
     * */
    public function delete(){
        $carno=input("param.carno");
        //dump($carno);exit;
        if($carno) {
            $result = Db::table('car')->where('carno', $carno)->delete();
            if (false != $result) {
                return $this->success('删除成功', $_SERVER['HTTP_REFERER']);
            } else {
                return $this->success('取消删除', $_SERVER['HTTP_REFERER']);
            }
        }else{
            return $this->success('取消删除', $_SERVER['HTTP_REFERER']);
        }

    }
    



}