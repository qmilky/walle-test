<?php

namespace Admin;

use DB;

    class TestController
    {
        // 拿到所有分类
        static public function getCates()
        {
            // 从数据库拿分类,返回数组
            $cates = (new DB('shop_cate'))->select();
            
            // 递归遍历数组,生成想要的结构
            return self::getLevelCate($cates,0);
        }
        
        // 遍历数组,生成想要的层级结构
        static public function getLevelCate($cates=null, $pid=0)
        {
            $sub = [];
            foreach($cates as $k=>$v){
                if ($v->pid == $pid) {
                    $v->sub =  self::getLevelCate($cates, $v->cid);
                    $sub[] = $v;
                }
            }
            return $sub;
        }
        
    }