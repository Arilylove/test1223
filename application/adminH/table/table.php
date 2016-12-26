<?php
/**
 * Created by PhpStorm.
 * User: HC
 * Date: 2016/11/7
 * Time: 13:44
 */
namespace app\adminH\table;
use think\Db;

/*
 * 事务操作
 * */
class table
{
    
    //事务开始
    public static function startTrans(){
        Db::startTrans();
    }

    //事务执行
    public static function commit(){
        Db::commit();
    }

    //事务回滚
    public static function rollback(){
        Db::rollback();
    }


}