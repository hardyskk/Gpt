<?php
/* *
 * 功能：彩虹易支付异步通知页面
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */

require_once("lib/epay.config.php");
require_once("lib/EpayCore.class.php");

//计算得出通知验证结果
$epay = new EpayCore($epay_config);
$verify_result = $epay->verifyNotify();

if($verify_result) {//验证成功

	//商户订单号
	$out_trade_no = $_GET['out_trade_no'];

	//彩虹易支付交易号
	$trade_no = $_GET['trade_no'];

	//交易状态
	$trade_status = $_GET['trade_status'];

	//支付方式
	$type = $_GET['type'];

	//支付金额
	$money = $_GET['money'];
	

	if ($_GET['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
		//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		//如果有做过处理，不执行商户的业务程序
		
        
        //判断当前订单是否已经做过处理
        $sql = "select sfcl from chat_order where dingdanhao='$out_trade_no'";
        $sfzgcl = $mysql->getOne($sql);
        
        
        
        if($sfzgcl == 1){
            echo '<script>location.href = "../../index.php";</script>';
            exit();
        }
        

		//根据订单号查询出用户
        $sql = "select yonghuming from chat_order where dingdanhao='$out_trade_no'";
        $user = $mysql->getOne($sql);
		
	
		//查询用户充值了多少个额度
		$sql = "select czdsed from chat_order where dingdanhao='$out_trade_no'";
        $czdsed = $mysql->getOne($sql);
		
		
		//判断是否开通会员
		if($czdsed == '1500'){
	
            // 获取下个月的时间戳
            $next_month_timestamp = strtotime('+1 month');
            
            // 将时间戳转换为日期格式
            $next_month_date = date('Y-m-d', $next_month_timestamp);
            
            //给用户添加额度
    		$sql = "update chat_yonghu set vip='$next_month_date' where yhmc='$user'";
    		$res = $mysql->query($sql);
    		if (!$res) {
    		    echo '充值失败 联系客服处理！！！';
    		    exit();
    		}
            $sql = "update chat_yonghu set sycs=sycs+$czdsed where yhmc='$user'";
    		$res = $mysql->query($sql);
    		if (!$res) {
    		    echo '充值失败 联系客服处理！！！';
    		    exit();
    		}
		}else if($czdsed == '10000'){
		    // 获取下个月的时间戳
            $next_month_timestamp = strtotime('+3 month');
            
            // 将时间戳转换为日期格式
            $next_month_date = date('Y-m-d', $next_month_timestamp);
            
            //给用户添加额度
    		$sql = "update chat_yonghu set vip='$next_month_date' where yhmc='$user'";
    		$res = $mysql->query($sql);
    		if (!$res) {
    		    echo '充值失败 联系客服处理！！！';
    		    exit();
    		}
    		$sql = "update chat_yonghu set sycs=sycs+$czdsed where yhmc='$user'";
    		$res = $mysql->query($sql);
    		if (!$res) {
    		    echo '充值失败 联系客服处理！！！';
    		    exit();
    		}
		}else if($czdsed == '999999999'){
		    // 获取下个月的时间戳
            $next_month_timestamp = strtotime('+12 month');
            
            // 将时间戳转换为日期格式
            $next_month_date = date('Y-m-d', $next_month_timestamp);
            
            //给用户添加额度
    		$sql = "update chat_yonghu set vip='$next_month_date' where yhmc='$user'";
    		$res = $mysql->query($sql);
    		if (!$res) {
    		    echo '充值失败 联系客服处理！！！';
    		    exit();
    		}
    		$sql = "update chat_yonghu set sycs=sycs+$czdsed where yhmc='$user'";
    		$res = $mysql->query($sql);
    		if (!$res) {
    		    echo '充值失败 联系客服处理！！！';
    		    exit();
    		}
		}else{
		    //给用户添加额度
    		$sql = "update chat_yonghu set sycs=sycs+$czdsed where yhmc='$user'";
    		$res = $mysql->query($sql);
    		if (!$res) {
    		    echo '充值失败 联系客服处理！！！';
    		    exit();
    		}
		}
		
		
		//如果充值成功后 把订单号改为已经处理过了
		$sql = "update chat_order set sfcl=1 where dingdanhao='$out_trade_no'";
		$res = $mysql->query($sql);
		if (!$res) {
		    echo '订单号处理错误 联系客服处理！';
		    exit();
		}
		

		
		echo '<script>location.href = "../../index.php";</script>';
		
		
	}

	//验证成功返回
	echo "success";
}
else {
	//验证失败
	echo "fail";
}
?>