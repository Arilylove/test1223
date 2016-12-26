<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/16
 * Time: 10:30
 */
namespace app\json\table;
use think\Db;
class Orders
{
    protected $tableName="order";
    protected $orderno;
    protected $status;
    protected $seatno;
    protected $sno;
    protected $ondate;
    protected $createtime;
    protected $startpos;
    protected $endpos;
    protected $carno;

    /*
     * 增
     * */
    public function add(){
        $data['orderno']=isset($this->orderno)?$this->orderno:'';
        $data['status']=isset($this->status)?$this->status:'';
        $data['seatno']=isset($this->seatno)?$this->seatno:'';
        $data['sno']=isset($this->sno)?$this->sno:'';
        $data['ondate']=isset($this->ondate)?$this->ondate:'';
        $data['createtime']=isset($this->createtime)?$this->createtime:'';
        $data['startpos']=isset($this->startpos)?$this->startpos:'';
        $data['endpos']=isset($this->endpos)?$this->endpos:'';
        $data['carno']=isset($this->carno)?$this->carno:'';
        
        $result=Db::table($this->tableName)->insert($data);
        return $result==1?0:1;
    }
    /*
     * 删
     * */
    public function delete($orderno){
        $re=Db::table($this->tableName)->delete($orderno);
        return $re==1?0:1;
    }
    /*
     * 改
     * */
    public function update(){
        $data['status']=$this->status;
        $data['seatno']=$this->seatno;
        $data['sno']=$this->sno;
        $data['ondate']=$this->ondate;
        $data['createtime']=date('Y-m-d H:i:s');
        $data['startpos']=$this->startpos;
        $data['endpos']=$this->endpos;
        $data['carno']=$this->carno;
        $re=Db::table($this->tableName)->update($data);
        return $re==1?0:1;
    }
    public function updateByStatus($orderno,$status){
        $data=['orderno'=>$orderno,'status'=>$status];
        $re=$re=Db::table($this->tableName)->update($data);
        return $re==1?0:1;
    }
    /*
     * 查
     * */
    public function find($orderno){
        $data=Db::table($this->tableName)->where('orderno',$orderno)->find();
        if($data){
            $this->orderno=$orderno;
            $this->status=$data['status'];
            $this->seatno=$data['seatno'];
            $this->sno=$data['sno'];
            $this->ondate=$data['ondate'];
            $this->createtime=$data['createtime'];
            $this->startpos=$data['startpos'];
            $this->endpos=$data['endpos'];
            $this->carno=$data['carno'];
            return 0;
        }
        return 1;
    }
    

    public function setOrderno($orderno){
        $this->orderno=$orderno;
    }
    public function getOrderno(){
        return $this->orderno;
    }
    public function setStatus($status){
        $this->status=$status;
    }
    public function getStatus(){
        return $this->status;
    }
    public function setSeatno($seatno){
        $this->seatno=$seatno;
    }
    public function getSeatno(){
        return $this->seatno;
    }
    public function setSno($sno){
        $this->sno=$sno;
    }
    public function getSno(){
        return $this->sno;
    }
    public function setOndate($ondate){
        $this->ondate=$ondate;
    }
    public function getOndate(){
        return $this->ondate;
    }
    public function setCreatetime($createtime){
        $this->createtime=$createtime;
    }
    public function getCreatetime(){
        return $this->createtime;
    }
    public function setStartpos($startpos){
        $this->startpos=$startpos;
    }
    public function getStartpos(){
        return $this->startpos;
    }
    public function setEndpos($endpos){
        $this->endpos=$endpos;
    }
    public function getEndpos(){
        return $this->endpos;
    }
    public function setCarno($carno){
        $this->carno=$carno;
    }
    public function getCarno(){
        return $this->carno;
    }

}