<?php

  
  class DB
  {
    static private $pdo;
    public $tableName;
    private $where=[];
    private $limit = '';
    private $orderBy = '';
    
    public  $nowPage=0; 
    public  $prevPage=0; 
    public  $nextPage=0; 
    public  $maxPage=0; 
    
    static public function getPDO()
    {
        if (empty(self::$pdo)){
           self::$pdo = new PDO(DSN, USER, UPWD);
           self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }
    
    public function __get($name)
    {
        return $this->$name;
    }
    
    // 通过该方法把条件保存到数组
    public function where($str)
    {
        $this->where[] = $str;
        
        return $this;
    }
    
    // 手动清空查询条件
    public function clearWhere()
    {
        $this -> where = [];
    }
    
    // orderBy 子句
    public function orderBy($str)
    {
        $this->orderBy = ' order by '.$str;
        return $this;
    }
    
    // 把条件数组中的条件拼成字符串
    public function getWhere()
    {
        $condition = '';
        // 如果条件数组不是空的
        if (!empty($this->where)) {
              $condition =  ' where '.implode(' and ', $this->where);
        }
        return $condition;
    }
    
    /*
        $bm  数据表的名称
    */
    function __construct($bm)
    {
        $this->tableName = $bm;
        if (empty(self::$pdo)){
           self::$pdo = new PDO(DSN, USER, UPWD);
           self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }
  
    function insert($data)
    {
        // 2.准备SQL语句
        $fields = '';
        $values = '';
        foreach($data as $k=>$v){
            $fields .= "$k,";
            $values .= "'$v',";
        }
        $fields = rtrim($fields, ',');
        $values = rtrim($values, ',');
    
        $sql = "insert into {$this->tableName}({$fields}) values({$values}) ";
        // echo $sql,'<br>';
        
        // 3.发送执行 返回受影响行数
        return self::$pdo -> exec($sql);
       
    }
    
    
    function delete()
    {
        // 2. 发送SQL语句给数据库服务器
        $sql = "delete from {$this->tableName} ".$this->getWhere();
        
        // echo $sql,'<br>';

        // 清除条件数组
        $this->where = [];
        
        return self::$pdo -> exec($sql);
    }
    
    
    function update($data)
    {
          $str = '';
          foreach($data as $k=>$v){
             $str .= "$k='$v',";
          }
          $str = rtrim($str,',');
        
          $sql = "update {$this->tableName} set {$str}  ".$this->getWhere();
          
          $this->where = [];

        // 发送执行
        return self::$pdo -> exec($sql);
    
    }
    
    
    // 查询多条
    function select()
    {
  
        // 2.准备SQL语句
        $sql = "select * from {$this->tableName} ".$this->getWhere()." {$this->orderBy}  {$this->limit}  ";
        // echo $sql,'<br>';

        $this->where = [];
        $this->orderBy = '';
        // 3.发送执行 返回预处理对象
        $res = self::$pdo -> query($sql);
        
        return $res -> fetchAll(PDO::FETCH_CLASS); // 读取出全部记录
    
    }
    
    
    public function limit($cnt, $perPage)
    {
             // 2. 获取当前页码
             $nowPage = empty($_GET['p']) ? 1 : $_GET['p'];
             
             // 3. 上一页,下一页,最后一页(最大页)
             $prevPage = $nowPage - 1;
             $nextPage = $nowPage + 1;
             
             // 最大页数
             $maxPage = ceil($cnt/$perPage);
             
             // 4.修正
             if ($nowPage <= 1){
                 $nowPage = 1;
                 $prevPage = 1;
             } elseif ($nowPage >= $maxPage) {
                 $nowPage = $maxPage;
                 $nextPage = $maxPage;
             }
             
             // 5.生成 limit = (当前页码-1)*每页记录数,每页记录数
             $this->limit = ' limit '.($nowPage-1)*$perPage.','.$perPage;
             
             $this -> nowPage = $nowPage; 
             $this -> prevPage = $prevPage; 
             $this -> nextPage = $nextPage; 
             $this -> maxPage = $maxPage; 
             
    }
    
    
    // 查询单条
    function find()
    {
       
        $sql = "select * from {$this->tableName} ".$this->getWhere()." limit 1";
        
        $res = self::$pdo -> query($sql);
        
        return $res -> fetch(PDO::FETCH_OBJ);
        
    }
    
    // 统计记录数
    public function rowCount()
    {
          $sql = "select count(*) renshu from {$this->tableName} ".$this->getWhere();
          $res = self::$pdo->query($sql);
          $obj = $res -> fetch(PDO::FETCH_OBJ);
          
          return $obj -> renshu;
    }
    
    
    
  }