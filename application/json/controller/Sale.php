<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/16
 * Time: 17:20
 */
namespace app\json\controller;
use app\json\table\Sales;
class Sale extends Base{
    /*
     * 销售统计
     * */
    public function saleCount(){
        $data['carno']=input('param.carno');
        $data['date']=input('param.date');
        //var_dump($data);exit;
        $validate=$this->validate($data,'Sale');
        //var_dump($validate);exit;
        if(true!==$validate){
            $this->setDesc(" $validate ");
            return 2;
        }
        $sa=new Sales();
        $saleNum=$sa->count($data['carno'],$data['date']);
        $price=50;
        //销售额
        $saleCount=$saleNum*$price;
        $this->setDesc('查询成功');
        $this->setResponseData(['carno'=>$data['carno'],'date'=>$data['date'],'saleNum'=>$saleNum,'saleCount'=>$saleCount]);
        return 0;
    }
    public function count(){
        $re=$this->saleCount();
        $data1=['retcode'=>$re,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }

    
}