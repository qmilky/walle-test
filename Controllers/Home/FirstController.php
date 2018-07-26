<?php

  /* 首页控制器 */
namespace Home;

use DB;
use Admin\CateController;

    class FirstController
    {
         // 显示首页
         public function first()
         {
            $cates = CateController::getCates();

            
            include('./Views/Home/First/first.html');
         }
    }