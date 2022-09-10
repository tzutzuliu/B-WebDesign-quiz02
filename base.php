<?php
session_start();
date_default_timezone_set("Asia/Taipei");


class DB{
   protected $table;
   protected $dsn='mysql:host=localhost;charset=utf8;dbname=db20';
   protected $pdo;


   function __construct($table)
   {
        $this->table=$table;
        $this->pdo=new PDO($this->dsn,'root','');
   }

   /**
    * 1.新增資料 insert() insert into table
    * 2.修改資料 update() update table set
    *   -> save()
    * 3.查詢資料 all(),find() select from table
    * 4.刪除資料 del() delete from table 
    * 5.計算 max(),min(),sum(),count(),avg() -> math() select max() from table
    * ($array) //特定欄位條件的多筆資料
    * ($sql)  //只有額外條件的多筆資料...limit $start,$div .... ,order by....,group by......
    * ($array,$sql) //有欄位條件又有額外條件的多筆資料....where  ..... limit ...., ..where ....order by.....
    * ($sql,$sql) //有欄位條件又有額外條件的多筆資料....where  ..... limit ...., ..where ....order by.....
    * ()  //整張資料表的內容
    */
   function all(...$arg){
        $sql="select * from $this->table ";
        if(isset($arg[0])){
            if(is_array($arg[0])){
                foreach($arg[0] as $key => $val){
                    $tmp[]="`$key`='$val'";
                }
                //$sql = $sql . " where " . join(" && ",$tmp);
                $sql .= " where " . join(" && ",$tmp);
            }else{
                // $sql=$sql . $arg[0];
                $sql .= $arg[0];
            }
        }

        if(isset($arg[1])){
            $sql .= $arg[1];
        }

        //echo $sql;
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
   }
   function find($arg){
    $sql="select * from $this->table where";
    
        if(is_array($arg)){
            foreach($arg as $key => $val){
                $tmp[]="`$key`='$val'";
            }
            //$sql = $sql . " where " . join(" && ",$tmp);
            $sql .=  join(" && ",$tmp);
        }else{
            // $sql=$sql . $arg[0];
            $sql .= " `id`='$arg'";
        }
   

    //echo $sql;
    return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
   }

   function save($array){
        if(isset($array['id'])){
            //更新
            foreach($array as $key => $val){
                $tmp[]="`$key`='$val'";
            }
            
            $sql="update $this->table set  ".join(',',$tmp)."  where `id`='{$array['id']}'";
        }else{
            $sql="insert into $this->table (`".join("`,`",array_keys($array))."`) 
                                     values('".join("','",$array)."')";
        }

        return $this->pdo->exec($sql);
   }

   function del($arg){
    $sql="delete from $this->table where";
    
        if(is_array($arg)){
            foreach($arg as $key => $val){
                $tmp[]="`$key`='$val'";
            }
            //$sql = $sql . " where " . join(" && ",$tmp);
            $sql .=  join(" && ",$tmp);
        }else{
            // $sql=$sql . $arg[0];
            $sql .= " `id`='$arg'";
        }
   

    //echo $sql;
    return $this->pdo->exec($sql);
   }
   function math($math,$col,...$arg){
    $sql="select $math($col) from $this->table ";
    if(isset($arg[0])){
        if(is_array($arg[0])){
            foreach($arg[0] as $key => $val){
                $tmp[]="`$key`='$val'";
            }
            //$sql = $sql . " where " . join(" && ",$tmp);
            $sql .= " where " . join(" && ",$tmp);
        }else{
            // $sql=$sql . $arg[0];
            $sql .= $arg[0];
        }
    }

    if(isset($arg[1])){
        $sql .= $arg[1];
    }

    //echo $sql;
    return $this->pdo->query($sql)->fetchColumn();
   }
   function q($sql){
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
   }

}


function dd($array){
echo "<pre>";

print_r($array);
echo "</pre>";
}

function to($url){
    header('location:'.$url);
}

$Total=new DB('total');
$User=new DB('user');
$News=new DB('news');
$Que=new DB('que');
$Log=new DB('log');

if(!isset($_SESSION['total'])){
    $chkDate=$Total->math('count','id',['date'=>date("Y-m-d")]);
    if($chkDate>=1){
        $total=$Total->find(['date'=>date("Y-m-d")]);
        $total['total']=$total['total']+1;
        $Total->save($total);
        $_SESSION['total']=1;
    }else{
        $Total->save(['date'=>date("Y-m-d"),'total'=>1]);
        $_SESSION['total']=1;
    }
}


?>