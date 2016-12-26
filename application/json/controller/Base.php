<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/21
 * Time: 11:05
 */
namespace app\json\controller;
use think\File;
use app\json\validate;
use think\Controller;
use think\App;
use think\Request;
use think\Db;
use think\Response;
class Base extends Controller{
    protected $responseData=[];
    protected $desc;
    protected $payData;
    /*
     * 事务操作；
     * */
    public static function startTrans() {
        Db::startTrans();
    }

    public static function commit() {
        Db::commit();
    }

    public static function rollback() {
        Db::rollback();
    }
    /*
     * 参数设置
     * */
    public function setDesc($desc){
        $this->desc=$desc;
    }
    public function getDesc(){
        return $this->desc;
    }
    public function setResponseData($responseData){
        $this->responseData=$responseData;
    }
    public function getResponseData(){
        return $this->responseData;
    }

    public function setPayData($payData){
        $this->payData=$payData;
    }
    public function getPayData(){
        return $this->payData;
    }

    /**
     * 输出返回数据
     * @access protected
     * @param mixed     $data 要返回的数据
     * @param String    $type 返回类型 JSON XML
     * @param integer   $code HTTP状态码
     * @return Response
     */
    protected function response($data, $type = 'json', $code = 200)
    {
        return Response::create($data, $type)->code($code);
    }
}
