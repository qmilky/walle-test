<?php

    // 权限控制器
namespace Admin;

use DB;
use PDO;

    class AuthController  extends CommonController
    {
        // 添加节点
        public function add()
        {
            include('./Views/Admin/Auth/insert.html');
        }
        
        // 把节点保存到表中
        public function doadd()
        {
            $data['node'] = $_POST['node'];
            $data['title'] = $_POST['title'];
            
            $ctrl = explode('@',$_POST['node'])[0];
            
            $data['ctrl'] = str_replace('Controller','',$ctrl);
            
            $row = (new DB('shop_nodes'))->insert($data);
            
            
            header('refresh:1;url=./index.php?m=admin&c=auth&a=add');
            die('添加成功');
            
        }
        
        // 浏览节点
        public function index()
        {
            // 拿数据
            $nodes = (new DB('shop_nodes'))->select();
            
            // 引入模板显示数据
            include('./Views/Admin/Auth/index.html');
        }
        
        
        
        // 添加角色
        public function createRole()
        {
            include('./Views/Admin/Auth/addrole.html');
        }
        
        public function doaddRole()
        {
            $row = (new DB('shop_roles'))->insert(['rname'=> $_POST['rname']]);
            header('refresh:1;url=./index.php?m=admin&c=auth&a=createrole');
            die('添加成功');
        }
        
        // 浏览角色
        public function rolelist()
        {
            // 拿数据
            $roles = (new DB('shop_roles'))->select();
            
            
            // 引入模板显示数据
            include('./Views/Admin/Auth/rolelist.html');
        }
        
        
        
        // 授权
        public function grant()
        {
            $rid = $_GET['rid'];
            
            // 拿到该角色已有的权限
            $nid_arr = (new DB('shop_grant'))->where("rid=$rid")->select();
            
            $nid_arr = array_column($nid_arr,'nid');
            // echo '<pre>';
            // print_r($nid_arr);die;
            
            $nodes = (new DB('shop_nodes'))->select();
        
        
            
            /*
            $arr = [];
            foreach($nodes as $k=>$v){
                $arr[$v->ctrl][] = $v;
            }
            
            echo '<pre>';
            print_r($arr);
            */
            
            // 引入模板显示数据
            include('./Views/Admin/Auth/nodelist.html');
            
        }
        
        
        // 接收授权页面的表单数据, 对某个角色的权限进行设置
        public function setauth()
        {
            
            $db = new DB('shop_grant');
            
            // 把该角色的所有权限全部删除掉
            $db->where("rid={$_GET['rid']}")->delete();
            
            // 把刚才提交过来的所有权限写入数据表
            foreach($_POST['auth'] as $k=>$v){
                $data['rid'] = $_GET['rid'];
                $data['nid'] = $v;
                $db -> insert($data);
            }
            
            header('refresh:2;url=./index.php?m=admin&c=auth&a=rolelist');
            die('保存设置成功...');
        }
        
        
    }