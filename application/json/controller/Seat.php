<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/16
 * Time: 17:20
 */
namespace app\json\controller;
use app\json\table\Cars;
use app\json\table\Schedules;
use app\json\table\Seatorder;
use app\json\table\Seatreal;
class Seat extends Base{
    /*
     * 座位订单信息查询
     * */
    public function sOrder(){
        $data['carno']=input('param.carno');
        $data['time']=input('param.time');
        $validate=$this->validate($data,'Seat');
        //var_dump($validate);exit;
        if(true!==$validate){
            $this->setDesc(" $validate ");
            return 2;
        }
        $time=$data['time'];
        $date=explode(" ",$data['time']);
        //var_dump($date);exit;
        $onDate=$date['0'];
        $onTime=$date['1'];
        //var_dump($time);exit;
        //先查询该车辆是否存在
        $car=new Cars();
        $carSe=$car->find($data['carno']);
        $carno=$data['carno'];
        if(0!=$carSe){
            $this->setDesc("查询的车辆 $carno 不存在");
            return 3;
        }
        //查询日程,该车辆该时间点有没有车次；
        $sc=new Schedules();
        $scSe=$sc->findByTime($carno,$onTime);
        if(0!=$scSe){
            $this->setDesc("车辆$carno 在 $onTime 点不存在");
            return 3;
        }
        $sno=$sc->getSno();
        //查询对应时间车辆该座位状态
        $sOrder=new Seatorder();
        //用一个参数显示座位结果：座位号+状态
        $seatStatus='';
        $seatnum=$car->getSeatnum();
        for($i=1;$i<=$seatnum;$i++){            //循环查询
            if(0!=$sOrder->queryStatusByIndex($sno,$time,$i)){
                $this->setDesc("$carno 车辆座位号 $sno 对应时间 $time 状态 $i 查询异常");
                return 3;
            }
            //
            if($i!=$seatnum){
                $seatStatus=$seatStatus.$i.'|'.$sOrder->getStatus().',';
            }else{
                $seatStatus=$seatStatus.$i.'|'.$sOrder->getStatus();
            }
        }
        $this->setDesc('获取状态成功');
        $this->setResponseData(['carno'=>$carno,'sno'=>$sno,'time'=>$time,'seatStatus'=>$seatStatus]);
        return 0;

    }
    public function sOrderSelect(){
        $re=$this->sOrder();
        $data1=['retcode'=>$re,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }
    /*
     * 座位实际信息查询
     * */
    public function sReal(){
        $carno=input('param.carno');
        /*$validate=$this->validate('carno','Seat');
        //var_dump($validate);exit;
        if(true!==$validate){
            $this->setDesc(" $validate ");
            $this->setResponseData([]);
            return 2;
        }*/
        //先查询该车辆是否存在
        $car=new Cars();
        $carSe=$car->find($carno);
        if(0!=$carSe){
            $this->setDesc("查询的车辆 $carno 不存在");
            return 3;
        }
        //查询对应时间车辆该座位状态
        $sReal=new Seatreal();
        //用一个参数显示座位结果：座位号+状态
        $seatStatus='';
        $seatnum=$car->getSeatnum();
        for($i=1;$i<=$seatnum;$i++){            //循环查询
            if(0!=$sReal->queryStatusByIndex($carno,$i)){
                $this->setDesc("$carno 车辆状态 $i 查询异常");
                return 3;
            }
            if($i!=$seatnum){
                $seatStatus=$seatStatus.$i.'|'.$sReal->getStatus().',';
            }else{
                $seatStatus=$seatStatus.$i.'|'.$sReal->getStatus();
            }
        }
        $this->setDesc('获取状态成功');
        $this->setResponseData(['carno'=>$carno,'seatStatus'=>$seatStatus]);
        return 0;
    }

    public function sRealSelect(){
        $re=$this->sReal();
        $data1=['retcode'=>$re,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }
    /*
     * 修改
     * */
    public function sRealStatus(){
        $data['carno']=input('param.carno');
        $data['status']=input('param.status');
        $validate=$this->validate($data,'Seat');
        if(true!==$validate){
            $this->setDesc(" $validate ");
            return 2;
        }
        $carno=$data['carno'];
        $seatStatus=explode(',',$data['status']);
        if($seatStatus==null||count($seatStatus)==0){
            $this->setDesc("要修改的座位数不能为0");
            return 2;
        }

        $car=new Cars();
        $find=$car->find($data['carno']);
        if(0!=$find){
            $this->setDesc("要修改的车辆 $carno 不存在");
            return 3;
        }
        /*
         * 需要开始事务操作
         * */
        $sReal=new Seatreal();
        Base::startTrans();
        for($i=0;$i<count($seatStatus);$i++){
            //1.座位格式不对(拆分座位号和状态)
            //var_dump($seatStatus);exit;
            $seatAndStatus=explode("|",$seatStatus[$i]);
            //var_dump($seatAndStatus);exit;
            if($seatAndStatus==null||count($seatAndStatus)!=2){
                $this->setDesc("要修改的座位数不能为0");
                Base::rollback();
                return 2;
            }
            //2.座位号超过总数
            $num=$car->getSeatnum();
            if($seatAndStatus['0']>$num){
                $this->setDesc("要修改的座位号不能超过总数 $num");
                Base::rollback();
                return 2;
            }
            //3.要修改的状态不支持
            if($seatAndStatus['1']==null||($seatAndStatus['1']!=0&&$seatAndStatus['1']!=1)){
                $this->setDesc("要修改的座位状态不支持，0表示未占用，1表示占用");
                Base::rollback();
                return 2;
            }
            //4.座位状态获取失败
            if(0!=$sReal->queryStatusByIndex($carno,$seatAndStatus['0'])){
                $this->setDesc("要修改的座位 $seatAndStatus[0] 获取失败");
                Base::rollback();
                return 2;
            }
            //5.座位号修改失败(实际状态不等于修改的状态)
            if($sReal->getStatus()!=$seatAndStatus['1']){
                if(0!=$sReal->updateByStatus($carno,$seatAndStatus['0'],$seatAndStatus['1'])){
                    $this->setDesc("要修改的座位 $seatAndStatus[0] 状态 $seatAndStatus[1]修改失败");
                    Base::rollback();
                    return 2;
                }
            }
        }
        Base::commit();
        $this->setDesc('修改成功');
        $this->setResponseData(['carno'=>$carno,'status'=>$data['status']]);
        return 0;

    }

    public function sRealUpdate(){
        $re=$this->sRealStatus();
        $data1=['retcode'=>$re,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }

    
}