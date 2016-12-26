<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/14
 * Time: 17:12
 */
namespace app\json\controller;
use app\json\table\Cars;

class Car extends Base
{
    /*
     * 添加车辆
     * */
    public function addCar(){
        $data['seatnum']=input('param.seatnum');
        $data['cardesc']=input('param.cardesc');
        $validate=$this->validate($data,'Car');
        if(true!==$validate){
            $this->setDesc(" $validate ");
            return 2;
        }
        $car=new Cars();
        if(0==$car->findByDesc($data['cardesc'])){
            $data['carno']=$car->getCarno();
            $this->setDesc('该车辆已存在');
            return 3;
        }
        $car->setCardesc($data['cardesc']);
        $car->setSeatnum($data['seatnum']);
        $re=$car->add();
        if($re!=0){
            $this->setDesc('添加数据库失败');
            return 3;
        }
        $this->setDesc('添加车辆成功');
        $this->setResponseData(['cardesc'=>$data['cardesc'],'seatnum'=>$data['seatnum']]);
        return 0;

    }
    public function add(){
        $result=$this->addCar();
        $data1=['retcode'=>$result,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }
    /*
     * 删除
     * */
    public function deleteCar(){
        $carno=input('param.carno');
        $car=new Cars();
        $car->setCarno($carno);
        $re=$car->delete($carno);
        if($re!=0){
            $this->setDesc('删除失败');
            return 3;
        }
        $this->setDesc('删除成功');
        $this->setResponseData(['carno'=>$carno]);
        return 0;
    }
    public function delete(){
        $re=$this->deleteCar();
        $data1=['retcode'=>$re,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }


    /*
     * 修改车辆信息
     * */
    
    
}