<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/12/22
 * Time: 17:16
 */
namespace app\json\controller;
use think\Db;
use app\json\table\Stations;
use app\json\table\Lines;
class Stationline extends Base{
    /*
     * 添加站点
     * */
    public function addSta(){
        $station['st_name']=input('param.st_name');
        $station['st_gps']=input('param.st_gps');
        $sta=new Stations();
        //var_dump($sta);exit;
        if(!$sta->findByGps($station['st_gps'])){
            $this->setDesc("该位置站点已存在");
            return 3;
        }
        $sta->setStname($station['st_name']);
        $sta->setStgps($station['st_gps']);
        $re=$sta->add();
        //var_dump($re);exit;
        if($re!=0){
            $this->setDesc('添加站点数据失败');
            return 3;
        }
        $this->setDesc('添加站点数据成功');
        $this->setResponseData(['st_name'=>$station['st_name'],'st_gps'=>$station['st_gps']]);
        return 0;     
                
    }
    public function addSt(){
        $result=$this->addSta();
        $data1=['retcode'=>$result,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }
    /*
     * 删除站点
     * */
    public function deleteSta(){
        $st_index=input('param.st_index');
        $sta=new Stations();
        $sta->setStindex($st_index);
        $re=$sta->delete($st_index);
        if($re!=0){
            $this->setDesc('删除失败');
            return 3;
        }
        $this->setDesc('删除成功');
        $this->setResponseData(['st_index'=>$st_index]);
        return 0;
    }
    public function delete(){
        $result=$this->deleteSta();
        $data1=['retcode'=>$result,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }
    /*
     * 修改站点信息
     * */
    public function updateSta(){
        $station['st_name']=input('param.st_name');
        $station['st_gps']=input('param.st_gps');
        $st_index=input('get.st_index');
        $sta=new Stations();
        $sta->setStname($station['st_name']);
        $sta->setStgps($station['st_gps']);
        $sta->setStindex($st_index);
        $re=$sta->update($st_index);
        if($re!=0){
            $this->setDesc('修改站点数据失败');
            return 3;
        }
        $this->setDesc('修改站点数据成功');
        $this->setResponseData(['st_name'=>$station['st_name'],'st_gps'=>$station['st_gps']]);
        return 0;
    }
    public function updateSt(){
        $re=$this->updateSta();
        $data1=['retcode'=>$re,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }
    /*
     * 线路添加
     * */
    public function addLine(){
        $st_index=input('param.st_index');
        if($st_index==null){
            $this->setDesc('有空参数');
            return 2;
        }
        //$st_index=preg_replace('，',',',$st_index);
        $station=explode(",",$st_index);
        //var_dump($station);exit;
        foreach ($station as $k=>$v){
            $st=Db::table('station')->where('st_index',$v)->find();
            if(!$st){
                $this->setDesc('站点不存在');
                return 3;
            }
        }
        $li=new Lines();
        $line=join("->",$station);
        $li->setLine($line);
        //var_dump($line);exit;
        $find=$li->findByLine($line);
        //var_dump($find);exit;
        if(!$find){
            $this->setDesc('线路已存在');
            return 3;
        }
        $re=$li->addByIndex($line);
        if($re!=0){
            $this->setDesc('线路添加失败');
            return 3;
        }
        $this->setDesc('线路添加成功');
        $this->setResponseData(['line'=>$line]);
        return 0;

    }
    public function addLi(){
        $re=$this->addLine();
        $data1=['retcode'=>$re,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }

    /*
   * 通过站点索引查询线路
   * */
    public function findByIndex(){
        $st_index=input('param.st_index');
        if($st_index==null){
            $this->setDesc('有空参数');
            return 2;
        }
        $line=Db::table('car_line')->select();
        $num=count($line);
        for($i=0;$i<$num;$i++){
            $station=$line[$i];
            $stationLine=explode(" ",$station['line']);
            //var_dump($stationLine);
            for($j=0;$j<count($stationLine);$j++){
                $sta=$stationLine[$j];
                //var_dump($sta);
                $st=explode(",",$sta);
                //var_dump($st);
                for($k=0;$k<count($st);$k++){
                    $index=$st[$k];
                    if($st_index==$index){
                        //要查找的线路
                        $findLine=$line[$i];
                        //var_dump($findLine);exit;
                        $this->setDesc('线路查找成功');
                        $this->setResponseData(['line'=>$findLine]);
                        return 0;
                    }
                }
            }
        }
        $this->setDesc('线路查找失败');
        return 3;
    }
    public function findIndex(){
        $re=$this->findBySta();
        $data1=['retcode'=>$re,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }

    /*
    * 通过站点查询线路(理论上)
    * */
    public function findBySta(){
        $st_name=input('param.st_name');
        if($st_name==null){
            $this->setDesc('有空参数');
            return 2;
        }
        $line=Db::table('car_line')->select();
        $num=count($line);
        //$station=array();
        for($i=0;$i<$num;$i++){
            $station=$line[$i];
            $stationLine=explode(" ",$station['line']);
            //var_dump($stationLine);
            for($j=0;$j<count($stationLine);$j++){
                $sta=$stationLine[$j];
                //var_dump($sta);
                $st=explode(",",$sta);
                //var_dump($st);
                for($k=0;$k<count($st);$k++){
                    $st_index=$st[$k];
                    //从表中查找的站点索引
                    $index=Db::table('station')->where('st_name',$st_name)->select();
                    $key=$index['st_index'];
                    if($key==$st_index){
                        //要查找的线路
                        $findLine=$line[$i];
                        //var_dump($findLine);exit;
                        $this->setDesc('线路查找成功');
                        $this->setResponseData(['line'=>$findLine]);
                        return 0;
                    }
                }
            }
        }
        $this->setDesc('线路查找失败');
        return 3;
    }
    public function findLine(){
        $re=$this->findBySta();
        $data1=['retcode'=>$re,'desc'=>$this->getDesc()];
        $data=array_merge($data1,$this->getResponseData());
        return $this->response($data,'json',200);
    }

    /*
     * 输入起点和终点，知道有几条线路并将线路输出（但是是以当前输入的终点为输出的线路的终点）???????
     * */
    public function lineEnd(){
        $index=input('param.index');
        $start=substr($index,0,1);
        $end=substr($index,-1);
        $endLine=$this->startEnd($start,$end);
        /*$starti=substr(substr($endLine,-2),-1);
        $endi=substr($endLine,-1);
        $endLine=substr($endLine,$starti,$endi+2*$endi+1);*/
        var_dump($endLine);exit;
        //var_dump($endLine);
       /* if($endLine==NULL) {
            $this->setDesc('未查找到该条线路');
            return 3;
        }*/

    }

    /*
     * 起点、终点站经过的线路和位置，0表示起点站（第一站）
     * */
    public function startEnd($start,$end){
        $line=Db::table('car_line')->select();
        //var_dump($line);exit;
        $num=count($line);
        for($i=0;$i<$num;$i++){
            $station=$line[$i]['line'];
            $startLine=$this->centerLine($station,$start);
            //var_dump($startLine);exit;
            if($startLine){
                $endLine=$this->centerLine($startLine,$end);
                if($endLine){
                    return  $endLine;
                }
            }
        }
        return NULL;

    }
    /*
     * 输出站点线路
     * */
    public function centerLine($station,$end){
        $stationLi=explode(" ",$station);
        //var_dump($station);exit;
        for($j=0;$j<count($stationLi);$j++) {
            $sta = $stationLi[$j];
            //var_dump($sta);exit;
            $st = explode("->", $sta);
            //var_dump($st);exit;
            for ($k = 0; $k < count($st); $k++) {
                $findIndex = $st[$k];
                if($end == $findIndex){
                    $endLine=$stationLi;
                    for($l=0;$l<count($endLine);$l++){
                        return $endLine[$l].'+'.$k;
                    }
                }
            }
        }
        return NULL;
    }


}
