<?php
/**
 * www.zhuizhan.com.
 * Date: 14-12-23 上午9:18
 */
namespace org\pay;
class Alipay{
    private $config = array();
    private $notify_url = '';
    private $return_url = '';
    private $dir        = '';
    private $host       = '';
    public function __construct($conf){
        header("Content-type:text/html;charset=utf-8");
        //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
        //合作身份者id，以2088开头的16位纯数字
        $config['partner']		= $conf['partner'];
        //安全检验码，以数字和字母组成的32位字符
        $config['key']			= $conf['key'];
        //↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
        //签名方式 不需修改
        $config['sign_type']    = strtoupper('MD5');
        //字符编码格式 目前支持 gbk 或 utf-8
        $config['input_charset']= strtolower('utf-8');
        //ca证书路径地址，用于curl中ssl校验
        //请保证cacert.pem文件在当前文件夹目录中
        $config['cacert']    = getcwd().'\\extend\\org\\pay\\alipay\\cacert.pem';
        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        $config['transport']    = 'http';
        $url = isset($conf['url']) ? $conf['url'] : '/pay/alipaynotify.html';
        $sync_url = isset($conf['url']) ? $conf['url'] : '/notify/alipaysyncnotify.html';
        $this->config = $config;
        $this->seller_email = '394925565@qq.com';
        $this->dir = getcwd().'/extend/org/pay/alipay/';
        $this->host = 'http://'.$_SERVER['HTTP_HOST'];
        $this->notify_url = 'http://www.yingloujie.cn'.$sync_url;    //服务器异步通知页面路径
        $this->return_url = $this->host.$url;
    }

    public function pay($data){
        //构造要请求的参数数组，无需改动
       // require_once($this->dir.'alipay_submit.class.php');
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            "partner" => trim($this->config['partner']),
            "payment_type"	=> 1,
            "notify_url"	=> $this->notify_url,
            "return_url"	=> $this->return_url,
            "seller_email"	=> $this->seller_email,
            "out_trade_no"	=> $data['out_trade_no'],   //商户订单号
            "subject"	=> $data['subject'],    //订单名称
            "total_fee"	=> $data['total_fee'],  //付款金额
            "body"	=> $data['body'],   //订单描述
            "show_url"	=> $this->host.'/'.$data['show_url'],   //商品展示地址 需以http://开头的完整路径，例如：http://www.xxx.com/myorder.html
            "anti_phishing_key"	=>time(),  //防钓鱼时间戳
            "exter_invoke_ip"	=> '', //客户端的IP地址
            "_input_charset"	=> trim(strtolower($this->config['input_charset']))
        );
        //建立请求
        $alipaySubmit = new  \org\pay\alipay\AlipaySubmit($this->config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "请稍候，正在跳转至付款页面");
        echo $html_text;
    }

    public function pay_notify(){
        //require_once($this->dir.'alipay_notify.class.php');
        //计算得出通知验证结果
        $alipayNotify = new \org\pay\alipay\AlipayNotify($this->config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //商户订单号
            $data['out_trade_no'] = $_POST['out_trade_no'];
            //支付宝交易号
            $data['trade_no'] = $_POST['trade_no'];
            //交易状态
             $data['trade_status'] = $_POST['trade_status'];

            return $data;

            /**
            if($_POST['trade_status'] == 'TRADE_FINISHED') {

            }else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {

            }**/
        }
        else {
            //验证失败
            return false;

        }
    }

    public function pay_return(){
        //require_once($this->dir.'alipay_notify.class.php');
        //计算得出通知验证结果
        $alipayNotify = new \org\pay\alipay\AlipayNotify($this->config);
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {//验证成功
            if($_GET['trade_status'] == 'TRADE_SUCCESS') {
                return $_GET;
            }
            else {
                return false;
            }
        }
        else {
           return false;
    }
    }
}