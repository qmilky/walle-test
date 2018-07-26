<?php
    /*商品控制器*/
namespace Admin;

use DB;
use UP;
use Image;
use PDO;

    class GoodsController extends CommonController
    {
        // 添加商品
        public function add()
        {
           // 获取分类信息
           $cates = (new DB('shop_cate'))->orderBy(" concat(path,cid,',') ")->select();
           
           $pid = array_column($cates,'pid'); // 得到所有父ID ,返回数组
           $pid = array_unique($pid);   // 去除重复的值,还是一个数组
           
           
           include('./Views/Admin/Goods/insert.html');
        }
        
        public function doadd()
        {
            // $data['cid'] = $_POST['cid'];
            // $data['gname'] = $_POST['gname'];
            // $data['price'] = $_POST['price'];
            // $data['stock'] = $_POST['stock'];
            // $data['gdesc'] = $_POST['gdesc'];
            // $data['status'] = $_POST['status'];
            // unset($_POST['reupwd']);
            $data = $_POST;
            $data['ctime'] = time();
            
            // $data['gpic'] = ?;
            
            // 处理上传文件
            $up = new UP(SAVEPATH, TYPE, SIZE);
            $up->upload($_FILES['gpic']);
            
            // 把上传图片压缩生成一个小图片
            $img = new Image(SAVEPATH);
            $img -> toSmImg($up->fileName);
            
            // 添加图片名称
            $data['gpic'] = $up->fileName;
            
            
            // 执行插入
            $row = (new DB('shop_goods'))->insert($data);
            if ($row) {
                echo '添加商品成功';
                header('refresh:2;url=./index.php?m=admin&c=goods&a=add');
                die;
            } else {
                echo '添加商品失败';
                header('refresh:2;url=./index.php?m=admin&c=goods&a=add');
                die;
            }
            
        }
        
        
        // 浏览商品
        public function index()
        {
            // 拿数据(商品)
            $db = new DB('shop_goods');
            
            
            // 判断是否传了查询分类的条件
            if (!empty($_GET['cid'])){
               $subcid = (new DB('shop_cate'))->where("  path like '%,{$_GET['cid']},%' ") -> select();
               $subcid = array_column($subcid,'cid');
               // // implode(',',$subcid);
               // echo '<pre>';
               // print_r($subcid);
               // echo '</pre>';
               $str = 'cid in('.implode(',',$subcid).",{$_GET['cid']})";
               $db->where($str);
            }
            /*
             // 方式1:
            $sql = "SELECT 
                        g.*,c.cname
                    FROM 
                        shop_goods g LEFT JOIN shop_cate c ON g.cid=c.cid
            ";
            $goods = $db->pdo->query($sql) -> fetchAll(PDO::FETCH_CLASS) ;
            */
            // echo '<pre>';
            // print_r($goods);
            // die;
            
            // 方式2
            $goods = $db ->select();

            
            // 拿所有类别, 只要cid 和 cname
            $catesAll = (new DB('shop_cate'))-> orderBy("concat(path,cid,',')")-> select(); 
            $cates = array_column($catesAll,'cname','cid');
            // echo '<pre>';
            // print_r($cates);
            // die;
            
            // $arr = [1=>'服装',2=>'男装',3=>'女装'];
            // $v->cid = 3;
            
            // echo  $arr[ $v->cid ];
            
            
            
            
            // 引入模板
            include('./Views/Admin/Goods/goodslist.html');
        }
        
        
        // 删除商品
        
        
        // 修改商品
        public function edit()
        {
            // 拿数据
            $gid = $_GET['gid'];
            $goods = (new DB('shop_goods'))->where("gid=$gid")->find(); // 拿到商品信息
            
            // 拿到类别信息
            $cates = (new DB('shop_cate'))->orderBy("concat(path,cid,',')")->select();
            
            // 生成一个所有父类数组
            $pid = array_column($cates,'pid');
            $pid = array_unique($pid);
            
            // 显示一个表单
            include('./Views/Admin/Goods/edit.html');
        }
        
        public function doedit()
        {
            $gid = $_GET['gid'];
            
            $data = $_POST;
            
            if ($_FILES['gpic']['error']!=4){
                 // 处理上传文件
                ($up = new UP(SAVEPATH, TYPE, SIZE))->upload($_FILES['gpic']);
                
                // 把上传图片压缩生成一个小图片
                (new Image(SAVEPATH)) -> toSmImg($up->fileName);
                
                // 添加图片名称
                $data['gpic'] = $up->fileName;
            }
            
            // 执行插入
            $row = (new DB('shop_goods'))->where("gid=$gid")->update($data);
            if ($row) {
                echo '修改商品成功';
                header('refresh:2;url=./index.php?m=admin&c=goods&a=index');
                die;
            } else {
                echo '修改商品失败';
                header('refresh:2;url=./index.php?m=admin&c=goods&a=edit&gid='.$gid);
                die;
            }
        }
        
        // 上架
        public function up($status=2)
        {
            $gid = $_GET['gid'];
            $data['status'] = $status;
            $row = (new DB('shop_goods')) -> where("gid=$gid") -> update($data);
            header('location:./index.php?m=admin&c=goods&a=index');
            die;
        }
        
        // 下架
        public function down()
        {
            $this->up(3);
        }
    }