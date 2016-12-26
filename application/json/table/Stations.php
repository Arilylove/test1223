<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/12/22
 * Time: 17:18
 */
namespace app\json\table;
use think\Db;
class Stations
{
    protected $tableName='station';
    protected $st_index;
    protected $st_name;
    protected $st_gps;
    /*
    * 增
    * */
    public function add(){
        $data['st_name']=isset($this->st_name)?$this->st_name:'';
        $data['st_gps']=isset($this->st_gps)?$this->st_gps:'';
        $result=Db::table($this->tableName)->insert($data);
        return $result==1?0:1;
    }
    /*
     * 删
     * */
    public function delete($st_index){
        $re=Db::table($this->tableName)->delete($st_index);
        return $re==1?0:1;
    }
    /*
     * 改
     * */
    public function update($st_index){
        $data['st_name']=$this->st_name;
        $data['st_gps']=$this->st_gps;
        $re=Db::table($this->tableName)->where('st_index',$st_index)->update($data);
        return $re==1?0:1;
    }
   
    /*
     * 查
     * */
    public function find($st_index){
        $data=Db::table($this->tableName)->where('st_index',$st_index)->find();
        if($data){
            $this->st_index=$st_index;
            $this->st_name=$data['st_name'];
            $this->st_gps=$data['st_gps'];
            return 0;
        }
        return 1;
    }
    public function findByGps($st_gps){
        $data=Db::table($this->tableName)->where('st_gps',$st_gps)->find();
        if($data){
            $this->st_name=$data['st_gps'];
            $this->st_index=$data['st_index'];
            $this->st_gps=$st_gps;
            return 0;
        }
        return 1;
    }
    public function setStindex($st_index){
        $this->st_index=$st_index;
    }
    public function getStindex(){
        return $this->st_index;
    }
    public function setStname($st_name){
        $this->st_name=$st_name;
    }
    public function getStname(){
        return $this->st_name;
    }
    public function setStgps($st_gps){
        $this->st_gps=$st_gps;
    }
    public function getStgps(){
        return $this->st_gps;
    }
}