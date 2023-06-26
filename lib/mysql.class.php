<?php 
// require('/lib/init.php');
class Mysql{
public function __construct(){
$this->conn();
}
public $link;
//链接数据库信息
public function conn(){
$cfg = include('config.php');
    $this->link = new mysqli($cfg['host'],$cfg['user'],$cfg['pwd'],$cfg['db']);
    if ($this->link->connect_errno) { // 判断是否连接成功
        echo "数据库链接失败，请到/lib/config.php配置数据库即可！";
        exit();
    }
    $this->query('set names '.$cfg['charset']);
}

//发送一条sql语句
public function query($sql) {
    
	$rs =  $this->link->query($sql);
	
	if($rs) {
		$this->mLog($sql);
	} else {
		$this->mLog($sql. "\n" . mysqli_error($this->link));
	}
	   
	return $rs;
}



/**
* log日志记录功能
*/
public function mLog($str) {
	$filename = ROOT . '/log/' . date('Ymd') . '.txt';
	$log = "-----------------------------------------\n".date('Y/m/d H:i:s') . "\n" . $str . "\n" . "-----------------------------------------\n\n";
	return file_put_contents($filename, $log , FILE_APPEND);
}





//获取全部数据
public function getAll($sql){
$res = $this->query($sql);
$data = [];
while ( $row = $res->fetch_assoc() ) {
$data[] = $row;
}

return $data;
}
//获取一条数据
public function getRow($sql){
$res = $this->query($sql);
$row = $res->fetch_assoc();
return $row;
}
//获取单个数据
public function getOne($sql){
$res = $this->query($sql);
$row = $res->fetch_row()[0];
return $row;
}

}

$mysql = new Mysql();
// print_r($mysql -> getOne('select * from xinxi'));

//作者无陌然qq2633544207
?>