<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/10
 * Time: 11:01
 */
namespace app\adminH\controller;
use think\Controller;
use app\adminH\model;
class Sales extends Controller{
    
    /*
     * 销售信息，需从座位使用情况来获取
     * */
    public function sales(){
        $sale['index']=input('post.index');  //索引

    }
    /*
     * 销售统计；从订单中统计结果，status为1的表示销售完成的,然后以索引的方式添加到sale表中，直接从sale表中统计；
     * */
    private function subSaleCount() {
        switch($this->_method) {
            case 'post':
                $data['carno'] = input('post.carno');
                $data['ondate'] = input('post.ondate');
                $data['status']=input('post.status');
                break;

            case 'get':
                $data['carno'] = input('get.carno');
                $data['ondate'] = input('get.ondate');
                $data['status']=input('get.status');
                break;

            default:
                $this->set("请求方法 $this->_method 不支持");
                return 1;
        }



    }

    public function saleCount() {
        $ret = $this->subSaleCount();

        $retDesc = ['retCode'=>$ret,'desc'=>$this->getDesc()];

        $data = array_merge($retDesc,$this->getResponseData());

        return $this->response($data,'json',200);
    }
}