<?php

    // 数据库配置
    const HOST = 'localhost';
    const DBNAME = 'myshop';
    const USER = 'root';
    const UPWD = '';
    const DSN = 'mysql:host='.HOST.';dbname='.DBNAME.';charset=utf8';
    
    
    // 文件上传配置
    const SAVEPATH = './Public/goods/';
    const TYPE = ['image/jpeg','image/png','image/gif'];
    const SIZE = 1024000;