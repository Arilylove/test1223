<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/12/22
 * Time: 17:24
 */
namespace app\json\table;
use think\Db;
class Lines{
    protected $tableName='car_line';
    protected $lid;
    protected $line;
    /*
    * 增
     * 
    * */
    public function addByIndex($station){
        //$station是数组格式的站点名称
        //组合索引，显示站点编号；
        //var_dump($station);exit;
        $line=join("->",$station);
        //var_dump($line);exit;
        $data=['line'=>$line];
        $re=Db::table($this->tableName)->insert($data);
        return $re==1?0:1;

    }

    public function delete($lid){
        $re=Db::table($this->tableName)->delete($lid);
        return $re==1?0:1;
    }
    /*
     * 查
     * */
    public function findByLine($line){
        $re=Db::table($this->tableName)->where('line',$line)->find();
        return $re==1?0:1;
    }
    public function findByLid($lid){
        $re=Db::table($this->tableName)->where('lid',$lid)->find();
        return $re==1?0:1;
    }
    public function findBySta($st_name){
        $line=Db::table($this->tableName)->select();
        $num=count($line);
        //$station=array();
        for($i=0;$i<$num;$i++){
            $station=$line[$i];
            $stationLine=explode(" ",$station['line']);
            //var_dump($stationLine);
            for($j=0;$j<count($stationLine);$j++){
                $sta=$stationLine[$j];
                //var_dump($sta);
                $st=explode("->",$sta);
                //var_dump($st);
                for($k=0;$k<count($st);$k++){
                    //$name=$st[$k];
                    if($st_name==$st[$k]){
                        return $stationLine[$j];
                    }
                }
            }
        }

    }  
    
    public function setLid($lid){
        $this->lid=$lid;
    }
    public function getLid(){
        return $this->lid;
    }
    public function setLine($line){
        $this->line=$line;
    }
    public function getLine(){
        return $this->line;
    }
    public function setStation($station){
        $this->station=$station;
    }
    public function getStation(){
        return $this->station;
    }
    
    

}