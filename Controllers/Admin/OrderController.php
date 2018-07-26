<?php

    // 后台订单
namespace Admin;

use DB;
use PDO;

    class OrderController
    {
        // 浏览订单
        public function index()
        {
            // 拿数据
            $db = new DB('shop_orders');
            $orders = $db -> select();
            
            $uid_arr = array_column($orders,'uid'); // 获取 uid 单列
            $uid_arr = array_unique($uid_arr);      // 去除重复 uid
            $uid_in = implode(',', $uid_arr);        // 拼成字符串
            
            // 获取相应的用户数据
            $users = (new DB('shop_users')) -> where("uid in($uid_in)") -> select();
            
            // 只要 uid=>uname
            $users = array_column($users, 'uname', 'uid');
            
            // 显示订单
            include('./Views/Admin/Order/orderlist.html');
            
        }
        
        
        // 订单详情
        public function show()
        {
            $oid = $_GET['oid'];
            
            // 1.获取 某个订单下的所有详细信息
            $sql = "
                SELECT 
                    d.*,g.gname,g.gpic
                FROM
                    shop_detail as d LEFT JOIN shop_goods g ON d.gid=g.gid
                WHERE
                    d.oid='$oid'
            ";
            
            $details = (new DB('shop_detail')) -> pdo -> query($sql)
                                                      -> fetchAll(PDO::FETCH_CLASS);
            
            // 显示订单中的商品明细
            include('./Views/Admin/Order/show.html');
        }
    
    }