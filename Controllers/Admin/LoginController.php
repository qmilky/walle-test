<?php

namespace Admin;

use DB;
use PDO;

    class LoginController
    {
        public function login()
        {
            // 显示登录页面
            include('./Views/Admin/Login/login.html');
        }
        
        public function dologin()
        {
            $code = $_POST['code'];
            $uname = $_POST['uname'];
            $upwd = md5($_POST['upwd']);
            
            // 判断输入的验证码和原来系统生成的验证 比较   strtolower 把内容全部转为小写
            if (strtolower($code) !== strtolower($_SESSION['code'])) {
                echo '验证码错误';
                header('refresh:2;url=./index.php?m=admin&c=login&a=login');
                die;
            }
            
            // 连接数据加 查询有没有指定名称和密码的人
            $db = new DB('shop_users');
            $user = $db -> where("uname='{$uname}'") 
                        -> where("upwd='{$upwd}'")->find();
            
            // 登录成功
            if ($user) {
                
                // 子查询, 获取该用所能访问的所有节点信息, 生成一个数组, 存放在 session 中
                $sql = "SELECT * FROM shop_nodes WHERE nid in ( select nid from shop_grant where rid={$user->auth} )";
                
                $nodes = DB::getPDO()->query($sql)->fetchAll(PDO::FETCH_CLASS);
                
                $_SESSION['node_arr'] = array_column($nodes, 'node');
                
            
                echo '登录成功,3秒后跳转.....';
                // 设置登录成功标志
                $_SESSION['adminFlag'] = true;
                $_SESSION['userInfo'] = $user;
                header('refresh:2;url=./index.php?m=admin&c=Login&a=index');
                die;
                
            } else {
                // 登录失败
                echo '用户名或密码错误!';
                header('refresh:2;url=./index.php?m=admin&c=login&a=login');
                die;
            }
        }
        
        // 退出
        public function logout()
        {
            echo '正在退出...';
            $_SESSION['adminFlag'] = false;
            $_SESSION['userInfo'] = null;
            header('refresh:2;url=./index.php?m=admin&c=login&a=login');
            die;
        }
        
        
        // 后台默认页
        public function index()
        {
            include('./Views/Admin/Login/index.html');
        }
        
        
    }