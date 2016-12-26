<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/14
 * Time: 17:12
 */
namespace app\json\controller;
use app\json\table\Orders;
use app\json\table\Seatorder;
use app\json\table\Schedules;
use app\json\table\Userorder;
use app\json\table\Sales;
class Order extends Base{
    protected $url="http://www.test.com/json";
    /*
     * 添加订单
     * */
    public function addOrder(){
        $user=input('param.userid');
        $order['sno']=input('param.sno');                  //日程编号
        $order['seatno']=input('param.seatno');           //座位号|状态，。。。
        $order['status']=input('param.status');
        $order['startpos']=input('param.startpos');       //出发点
        $order['endpos']=input('param.endpos');           //到达点
        $order['carno']=input('param.carno');             //对应的车辆编号
        $order['ondate']=input('param.ondate');           //订车辆的出发时间
        $order['createtime']=date('Y-m-d H:i:s');         //订单创建时间
        //验证信息是否合理
        $validate=$this->validate($order,'Order');
        if(true!==$validate){
            $this->setDesc(" $validate ");
            return 2;
        }
        //1.订单创建的时间是否在订单出发的时间30分钟之前；
        if((strtotime($order['ondate'])-strtotime($order['createtime']))<60*30){
            $timeCut=(strtotime($order['ondate'])-strtotime($order['createtime']))/60;
            $this->setDesc("车辆 $timeCut 后即将出发，不在购票时间内");
            return 2;
        }
        //2.座位是否符合规定？
        $seatMul=explode(',',$order['seatno']);
        //var_dump($seatMul);exit;

        //3.车辆对应时间点的车次
        $sc=new Schedules();
        if(0!=$sc->findByTime($order['carno'],$order['ondate'])){
            $carno=$order['carno'];
            $ondate=$order['ondate'];
            $this->setDesc("车辆 $carno 在 $ondate 点对应车次未找到");
            return 2;
        }

        //4.座位对应的状态 1表示空缺，2表示已锁定，。。
        $sno=$sc->getSno();
        foreach($seatMul as $item){
            $sOrder=new Seatorder();
            if(0!=$sOrder->queryStatusByIndex($sno,$order['createtime'],$item)){
                $this->setDesc("座位 $item 获取失败");
                return 4;
            }
            if($sOrder->getStatus()==1){
                continue;
            }else if($sOrder->getStatus()==2){
                $this->setDesc("座位已锁定");
                return 4;
            }else{
                $this->setDesc("座位已售");
                return 4;
            }
        }

        //修改座位状态为2，事务操作
        Base::startTrans();
        foreach ($seatMul as $item){
            $sOrder=new Seatorder();
            //var_dump($item);exit;
            if(0!=$sOrder->updateByStatus($order['sno'],$order['createtime'],$item,2)){
                $this->setDesc("座位 $item 订购失败");
                Base::rollback();
                return 5;
            }
        }
        //添加
        $addOrder=new Orders();
        //var_dump($addOrder);exit;
        $addOrder->setSno($order['sno']);
        $addOrder->setCarno($order['carno']);
        $addOrder->setOrderno(time().mt_rand(10000,99999));
        $addOrder->setSeatno($order['seatno']);
        $addOrder->setOndate($order['ondate']);
        $addOrder->setCreatetime($order['createtime']);
        $addOrder->setStartpos($order['startpos']);
        $addOrder->setEndpos($order['endpos']);
        //1.添加数据库
        if(0!=$addOrder->add()){
            $this->setDesc('订单添加失败');
            Base::rollback();
            return 5;
        }
        //2.用户与订单绑定
        $userOrder=new Userorder();
        $orderno=$addOrder->getOrderno();
        if($user){
            $userOrder->setOrder($orderno);
            $userOrder->setUser($user);
            if(0!=$user->add()){
                $this->setDesc('用户与订单绑定失败');
                Base::rollback();
                return 5;
            }
        }
        Base::commit();
        $this->setDesc('订单添加成功');
        $price=50;
        $time=date('Y-m-d H:i:s');
        $countPrice=count($seatMul)*$price;
        $codeUrl=urlencode($this->url.'/order/payGet/seatno='.$order['seatno'].'&orderno='.$orderno.'&startpos='.$order['startpos']
            .'&endpos='.$order['endpos'].'&sno='.$order['sno'].'&ondate='.$order['ondate'].'&createtime='.$time.'&price='.$countPrice);
        $this->setResponseData(['order'=>$orderno,'seatno'=>$order['seatno'],'startpos'=>$order['startpos'],'endpos'=>$order['endpos'],
            'ondate'=>$order['ondate'],'createtime'=>$time,'price'=>$countPrice,'codeUrl'=>$codeUrl]);
        return 0;

    }
    public function add(){
        $re=$this->addOrder();
        $data1=['retcode'=>$re,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }
    /*
     * 支付页面
     * */
    public function payGetOrder(){
        $seatno=input('param.seatno');
        $orderno=input('param.orderno');
        $startpos=input('param.startpos');
        $endpos=input('param.endpos');
        $ondate=input('param.ondate');
        $createtime=input('param.createtime');
        $price=input('param.price');
        $seatMul=explode(',',$seatno);
        $seatnum=count($seatMul);
        $this->setPayData(['seatno'=>$seatno,'orderno'=>$orderno,'startpos'=>$startpos,'endpos'=>$endpos,'ondate'=>$ondate,
            'createtime'=>$createtime,'price'=>$price,'seatnum'=>$seatnum,'url'=>$this->url]);
        return 0;
    }
    public function payGet(){
        if(0==$this->payGetOrder()){
            return $this->fetch('pay',$this->getPayData());
        }
        print('GET PAYINFO ERROR');
    }
    /*
     * 支付
     * */
    public function payInfo(){
        $orderno=input('param.orderno');
        $order=new Orders();
        //1.支付订单不存在
        if(0!=$order->find($orderno)){
            $this->setDesc('支付订单不存在');
            return 3;
        }
        //2.订单失效（超过30分钟未支付）
        $timeNow=date('Y-m-d H:i:s');
        if((strtotime($timeNow)-strtotime($order->getCreatetime()))>60*30){
            $this->setDesc('订单已失效');
            return 3;
        }
        //3.订单已支付
        if($order->getStatus()==2){
            $this->setDesc('订单已支付');
            return 3;
        }
        //开始占座
        Base::startTrans();
        $sOrder=new Seatorder();   //座位订单状态
        $sale=new Sales();         //销售量

        $seatno=$order->getSeatno();
        $seatMul=explode(',',$seatno);
        //var_dump(seatMul[0]);exit;
        for($i=0;$i<count($seatMul);$i++){
            //1.座位订单状态更改
            //var_dump($order->getOndate());exit;
            if(0 !=$sOrder->updateByStatus($order->getSno(),$order->getCreatetime(),$seatMul[$i],3)){
                $this->setDesc('座位订单状态更改失败');
                Base::rollback();
                return 4;
            }
            //2.销售状态更改,日期显示的是出发当天的销售状态，不写$order->getCreatetime()；
            if(0 !=$sale->addByIndex($order->getCarno(),$order->getOndate(),$seatMul[$i])){
                $this->setDesc('销售状态更改失败');
                Base::rollback();
                return 4;
            }

        }
            //3.订单状态更改
            if(0!=$order->updateByStatus($orderno,2)){
                $this->setDesc('订单状态更改失败');
                Base::rollback();
                return 5;
            }
        Base::commit();
        $this->setDesc('支付成功');
        return 0;
    }
    public function pay(){
        $result=$this->payInfo();
        $data1=['retcode'=>$result,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);

    }
    public function apiPay(){
        $re=$this->payInfo();
        $data1=['retcode'=>$re,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }

    /*
     * 订单状态查询
     * */
    public function payStatusGet(){
        $orderno=input('param.orderno');
        $order=new Orders();
        if(0!=$order->find($orderno)){
            $this->setDesc("订单 $orderno 查询失败");
            return 3;
        }
        $status= $order->getStatus();
        $this->setDesc('查询成功');
        $this->setResponseData(['orderno'=>$orderno,'status'=>$status]);
        return 0;

    }
    public function payStatus(){
        $re=$this->payStatusGet();
        $data1=['retcode'=>$re,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }

    /*
     * 订单信息查询
     * */
    public function getOrder(){
        $orderno=input('param.orderno');
        $order=new Orders();
        if(0!=$order->find($orderno)){
            $this->setDesc("订单 $orderno 查询失败");
            return 3;
        }
        $this->setDesc('查询成功');
        $this->setResponseData(['orderno'=>$orderno,'carno'=>$order->getCarno(),'seatno'=>$order->getSeatno(),
            'startpos'=>$order->getStartpos(),'endpos'=>$order->getEndpos(),'ondate'=>$order->getOndate(),'sno'=>$order->getSno(),
            'createtime'=>$order->getCreatetime()]);
        return 0;

    }
    public function get(){
        $re=$this->getOrder();
        $data1=['retcode'=>$re,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }

    

}