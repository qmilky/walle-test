<?php

namespace Admin;

    class CommonController
    {
        public function __construct()
        {
            // 判断是否登录成功
            if (empty($_SESSION['adminFlag'])) {
                echo '您还没有登录,请选登录...';
                header('refresh:2;url=./index.php?m=admin&c=login&a=login');
                die;
            }
            
            // 判断是否有权限访问
            $node = ucfirst($_GET['c']).'Controller@'.$_GET['a'];  // 准备要访问的地方
            if (!in_array($node, $_SESSION['node_arr'])) {
                
                if ($_SESSION['userInfo']->auth == 3) {
                    header('refresh:1;url=./index.php?m=home&c=first&a=first');
                    die('您找到页面不存在 404!');
                } else {
                    header('refresh:1;url=./index.php?m=admin&c=login&a=index');
                    die('权限不够....2秒后跳转...');
                }
                
            }
        }
    }