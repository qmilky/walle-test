<?php

    session_start();
    
    // 接收命令
    $m = empty($_GET['m']) ? 'home' : $_GET['m'];
    $c = empty($_GET['c']) ? 'first': $_GET['c'];
    $a = empty($_GET['a']) ? 'first': $_GET['a'];

    // 引入异常处理
    include('./Public/doexception.php');
    
    // 引入配置文件
    include('./Config/config.php');
    
    // 引入自动加载处理函数
    include('./Public/autoload.php');

    // 拼接类名称
    $clsName = '\\'.ucfirst($m).'\\'.ucfirst($c).'Controller';       // Admin\UserController
    // 实例化,调用相应的成员方法
    ( new $clsName ) -> $a();