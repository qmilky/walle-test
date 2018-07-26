<?php

namespace Admin;

use DB;

    class UserController extends CommonController
    {
        // 添加用户
        public function add()
        {
           // 显示一个表单
           include('./Views/Admin/User/insert.html');
        }
        
        public function doadd()
        {
            if (empty($_POST['upwd']) || empty($_POST['reupwd'])){
                header('refresh:2;url=./index.php?m=admin&c=user&a=add');
                die('密码不能为空');
            }
            if ($_POST['upwd']!==$_POST['reupwd']){
                header('refresh:2;url=./index.php?m=admin&c=user&a=add');
                die('两次密码不一致');
            }
            
            $data['auth'] = $_POST['auth'];
            $data['uname'] = $_POST['uname'];
            $data['upwd'] = md5($_POST['upwd']);
            $data['tel'] = $_POST['tel'];
            $data['sex'] = $_POST['sex'];
            $data['regtime'] = time();
            
            $row = (new DB('shop_users')) -> insert($data);
            
            if ($row){
               echo '添加用户成功';
            } else {
               echo '添加用户失败';
            }
            header('refresh:2;url=./index.php?m=admin&c=user&a=add');
            die;
            
        }
        
        
        // 浏览用户
        public function index()
        {
            // echo $_SERVER['QUERY_STRING'],'<BR>';
           
             // 拿数据
             $db = new DB('shop_users');
             
             $tj = '';
             
             // 如果传了性别条件
             if (!empty($_GET['sex'])) {
                  $db -> where(" sex='{$_GET['sex']}' ");
                  $tj .= "&sex={$_GET['sex']}";
             }
             
             // 如果传了姓名条件
             if (!empty($_GET['uname'])){
                  $db -> where(" uname like '%{$_GET['uname']}%' ");
                  $tj .= "&uname={$_GET['uname']}";
             }
             
            
             // 统计符合条件的记录总数
             $cnt = $db -> rowCount();
             
             // 生成 limit 字符串, 参数: 1)记录总数  2)每页显示记录数
             $db -> limit($cnt, 5);
              
             
             // 查询
             $users = $db -> select();
             
             // 显示数据
             include('./Views/Admin/User/userlist.html');
        }
        
        
        // 删除用户
        public function del()
        {
            $uid = $_GET['uid'];
            
            $row = (new DB('shop_users') ) -> where("uid=$uid") -> delete();
            
            if ($row) {
               echo '删除成功';
            } else {
                echo '删除失败';
            }
             header('refresh:2;url=./index.php?m=admin&c=user&a=index');
             die;
            
        }
        
        
        // 修改用户
        public function edit()
        {
            // 拿数据
            $uid = $_GET['uid'];
            $user = (new DB('shop_users')) -> where(" uid=$uid ") -> find();

            // 显示到表单
            include('./Views/Admin/User/edit.html');
        }
        
        public function doedit()
        {
            $uid = $_GET['uid'];
            
            $data['auth'] = $_POST['auth'];
            $data['uname'] = $_POST['uname'];
            $data['sex'] = $_POST['sex'];
            $data['tel'] = $_POST['tel'];
            
            // 实例化, 存入条件,执行修改,返回受影响行数
            $row = (new DB('shop_users')) -> where(" uid=$uid ") -> update($data);
            
            if ($row){
                echo '修改成功';
                header('refresh:2;url=./index.php?m=admin&c=user&a=index');
                die;
            } else {
                echo '修改失败';
                header('refresh:2;url=./index.php?m=admin&c=user&a=edit&uid='.$uid);
                die;
            }
            
            
        }
    
    
    }