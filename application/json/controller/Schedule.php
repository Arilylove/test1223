<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/16
 * Time: 17:20
 */
namespace app\json\controller;
use app\json\table\Schedules;
class Schedule extends Base{
    /*
         * 添加日程信息
         * */
    public function scheduleAdd(){
        $data['carno']=input('param.carno');
        $data['timestart']=input('param.timestart');
        $data['timeend']=input('param.timeend');
        $data['lid']=input('param.lid');
        $validate=$this->validate($data,'Schedule');
        //var_dump($validate);exit;
        if(true!==$validate){
            $this->setDesc(" $validate ");
            return 2;
        }
        $sc=new Schedules();
        $sc->setCarno($data['carno']);
        $sc->setTimestart($data['timestart']);
        $sc->setTimeend($data['timeend']);
        $sc->setLid($data['lid']);
        $re=$sc->add();
        if($re!=0){
            $this->setDesc('添加失败');
            return 3;
        }
        $this->setDesc('添加日程成功');
        $this->setResponseData(['carno'=>$data['carno'],'timestart'=>$data['timestart'],'timeend'=>$data['timeend'],'lid'=>$data['lid']]);
        return 0;
    }
    public function addSc(){
        $result=$this->scheduleAdd();
        $data1=['retcode'=>$result,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }

    /*
     * 删除
     * */
    public function deleteSc(){
        $sno=input('param.sno');
        $sc=new Schedules();
        $re=$sc->delete($sno);
        if(0!=$re){
            $this->setDesc('删除失败');
            return 3;
        }
        $this->setDesc('删除成功');
        $this->setResponseData(['sno'=>$sno]);
        return 0;
    }
    public function delete(){
        $result=$this->deleteSc();
        $data1=['retcode'=>$result,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }
    


    
}