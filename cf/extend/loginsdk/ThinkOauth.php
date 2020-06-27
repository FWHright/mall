<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// | ThinkOauth.class.php 2013-02-25
// +----------------------------------------------------------------------
namespace loginsdk;
abstract class ThinkOauth{
	/**
	 * oauth版本
	 * @var string
	 */
	protected $Version = '2.0';
	
	/**
	 * 申请应用时分配的app_key
	 * @var string
	 */
	protected $AppKey = '';
	
	/**
	 * 申请应用时分配的 app_secret
	 * @var string
	 */
	protected $AppSecret = '';
	
	/**
	 * 授权类型 response_type 目前只能为code
	 * @var string
	 */
	protected $ResponseType = 'code';
	
	/**
	 * grant_type 目前只能为 authorization_code
	 * @var string 
	 */
	protected $GrantType = 'authorization_code';
	
	/**
	 * 回调页面URL  可以通过配置文件配置
	 * @var string
	 */
	protected $Callback = '';
	
	/**
	 * 获取request_code的额外参数 URL查询字符串格式
	 * @var srting
	 */
	protected $Authorize = '';
	
	/**
	 * 获取request_code请求的URL
	 * @var string
	 */
	protected $GetRequestCodeURL = '';
	
	/**
	 * 获取access_token请求的URL
	 * @var string
	 */
	protected $GetAccessTokenURL = '';

	/**
	 * API根路径
	 * @var string
	 */
	protected $ApiBase = '';
	
	/**
	 * 授权后获取到的TOKEN信息
	 * @var array
	 */
	protected $Token = null;

	/**
	 * 调用接口类型
	 * @var string
	 */
	private $Type = '';
	
	/**
	 * 构造方法，配置应用信息
	 * @param array $token 
	 */
	public function __construct($token = null){
		//设置SDK类型
		$class = get_class($this);
        $class = str_replace('loginsdk\\','',$class);
		$this->Type = strtolower(substr($class, 0, strlen($class)-3));

		//获取应用配置
		$config = cache("user")[$this->Type];
		if(empty($config['app_key']) || empty($config['app_secret'])){
			throw new \Exception('请配置您申请的APP_KEY和APP_SECRET');
		} else {
			$this->AppKey    = $config['app_key'];
			$this->AppSecret = $config['app_secret'];
			$this->Token     = $token; //设置获取到的TOKEN
		}
	}

	/**
     * 取得Oauth实例
     * @static
     * @return mixed 返回Oauth
     */
    public static function getInstance($type, $token = null) {
    	$name = ucfirst(strtolower($type)) . 'SDK';
    	require_once "sdk".DS."{$name}.class.php";
        $name = "\\loginsdk\\".$name;
       return new $name($token);

    }

	/**
	 * 初始化配置
	 */
	private function config(){
		$config = cache("user")[$this->Type];
		if(!empty($config['AUTHORIZE']))
			$this->Authorize = $config['AUTHORIZE'];
		if(!empty($config['callback']))
			$this->Callback = $config['callback'];
		else
			throw new \Exception('请配置回调页面地址');
	}
	
	/**
	 * 请求code 
	 */
	public function getRequestCodeURL(){
		$this->config();
		//Oauth 标准参数
		$params = array(
			'client_id'     => $this->AppKey,
			'redirect_uri'  => $this->Callback,
			'response_type' => $this->ResponseType,
		);
		
		//获取额外参数
		if($this->Authorize){
			parse_str($this->Authorize, $_param);
			if(is_array($_param)){
				$params = array_merge($params, $_param);
			} else {
				throw new \Exception('AUTHORIZE配置不正确！');
			}
		}
		return $this->GetRequestCodeURL . '?' . http_build_query($params);
	}
	
	/**
	 * 获取access_token
	 * @param string $code 上一步请求到的code
	 */
	public function getAccessToken($code, $extend = null){
		$this->config();
		$params = array(
				'client_id'     => $this->AppKey,
				'client_secret' => $this->AppSecret,
				'grant_type'    => $this->GrantType,
				'code'          => $code,
				'redirect_uri'  => $this->Callback,
		);

		$data = $this->http($this->GetAccessTokenURL, $params, 'POST');
		$this->Token = $this->parseToken($data, $extend);
		return $this->Token;
	}

	/**
	 * 合并默认参数和额外参数
	 * @param array $params  默认参数
	 * @param array/string $param 额外参数
	 * @return array:
	 */
	protected function param($params, $param){
		if(is_string($param))
			parse_str($param, $param);
		return array_merge($params, $param);
	}

	/**
	 * 获取指定API请求的URL
	 * @param  string $api API名称
	 * @param  string $fix api后缀
	 * @return string      请求的完整URL
	 */
	protected function url($api, $fix = ''){
		return $this->ApiBase . $api . $fix;
	}
	
	/**
	 * 发送HTTP请求方法，目前只支持CURL发送请求
	 * @param  string $url    请求URL
	 * @param  array  $params 请求参数
	 * @param  string $method 请求方法GET/POST
	 * @return array  $data   响应数据
	 */
	protected function http($url, $params, $method = 'GET', $header = array(), $multi = false){
		$opts = array(
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_HTTPHEADER     => $header
		);

		/* 根据请求类型设置特定参数 */
		switch(strtoupper($method)){
			case 'GET':
				$opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
				break;
			case 'POST':
				//判断是否传输文件
				$params = $multi ? $params : http_build_query($params);
				$opts[CURLOPT_URL] = $url;
				$opts[CURLOPT_POST] = 1;
				$opts[CURLOPT_POSTFIELDS] = $params;
				break;
			default:
				throw new \Exception('不支持的请求方式！');
		}
		
		/* 初始化并执行curl请求 */
		$ch = curl_init();
		curl_setopt_array($ch, $opts);
		$data  = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);
		if($error) throw new \Exception('请求发生错误：' . $error);
		return  $data;
	}
	
	/**
	 * 抽象方法，在SNSSDK中实现
	 * 组装接口调用参数 并调用接口
	 */
	abstract protected function call($api, $param = '', $method = 'GET', $multi = false);
	
	/**
	 * 抽象方法，在SNSSDK中实现
	 * 解析access_token方法请求后的返回值
	 */
	abstract protected function parseToken($result, $extend);
	
	/**
	 * 抽象方法，在SNSSDK中实现
	 * 获取当前授权用户的SNS标识
	 */
	abstract public function openid();

    //登录成功，获取腾讯QQ用户信息
    public function qq($token){
        $qq   = ThinkOauth::getInstance('qq', $token);
        $data = $qq->call('user/get_user_info');
        if($data['ret'] == 0){
            $userInfo['type'] = 'QQ';
            $userInfo['name'] = $data['nickname'];
            $userInfo['nick'] = $data['nickname'];
            $userInfo['head'] = $data['figureurl_2'];
            return $userInfo;
        } else {
            throw_exception("获取腾讯QQ用户信息失败：{$data['msg']}");
        }
    }

    //登录成功，获取腾讯微博用户信息
    public function tencent($token){
        $tencent = ThinkOauth::getInstance('tencent', $token);
        $data    = $tencent->call('user/info');

        if($data['ret'] == 0){
            $userInfo['type'] = 'TENCENT';
            $userInfo['name'] = $data['data']['name'];
            $userInfo['nick'] = $data['data']['nick'];
            $userInfo['head'] = $data['data']['head'];
            return $userInfo;
        } else {
            throw_exception("获取腾讯微博用户信息失败：{$data['msg']}");
        }
    }

    //登录成功，获取新浪微博用户信息
    public function sina($token){
        $sina = ThinkOauth::getInstance('sina', $token);
        $data = $sina->call('users/show', "uid={$sina->openid()}");

        if($data){
            $userInfo['type'] = 'SINA';
            $userInfo['name'] = isset($data['name']) ? $data['name'] : '';
            $userInfo['nick'] = isset($data['screen_name']) ? $data['screen_name'] : '';
            $userInfo['head'] = isset($data['screen_name']) ? $data['avatar_large'] : '';
            return $userInfo;
        } else {
            throw_exception("获取新浪微博用户信息失败：{$data['error']}");
        }
    }

    //登录成功，获取网易微博用户信息
    public function t163($token){
        $t163 = ThinkOauth::getInstance('t163', $token);
        $data = $t163->call('users/show');

        if($data['error_code'] == 0){
            $userInfo['type'] = 'T163';
            $userInfo['name'] = $data['name'];
            $userInfo['nick'] = $data['screen_name'];
            $userInfo['head'] = str_replace('w=48&h=48', 'w=180&h=180', $data['profile_image_url']);
            return $userInfo;
        } else {
            throw_exception("获取网易微博用户信息失败：{$data['error']}");
        }
    }

    //登录成功，获取人人网用户信息
    public function renren($token){
        $renren = ThinkOauth::getInstance('renren', $token);
        $data   = $renren->call('users.getInfo');

        if(!isset($data['error_code'])){
            $userInfo['type'] = 'RENREN';
            $userInfo['name'] = $data[0]['name'];
            $userInfo['nick'] = $data[0]['name'];
            $userInfo['head'] = $data[0]['headurl'];
            return $userInfo;
        } else {
            throw_exception("获取人人网用户信息失败：{$data['error_msg']}");
        }
    }

    //登录成功，获取360用户信息
    public function x360($token){
        $x360 = ThinkOauth::getInstance('x360', $token);
        $data = $x360->call('user/me');

        if($data['error_code'] == 0){
            $userInfo['type'] = 'X360';
            $userInfo['name'] = $data['name'];
            $userInfo['nick'] = $data['name'];
            $userInfo['head'] = $data['avatar'];
            return $userInfo;
        } else {
            throw_exception("获取360用户信息失败：{$data['error']}");
        }
    }

    //登录成功，获取豆瓣用户信息
    public function douban($token){
        $douban = ThinkOauth::getInstance('douban', $token);
        $data   = $douban->call('user/~me');

        if(empty($data['code'])){
            $userInfo['type'] = 'DOUBAN';
            $userInfo['name'] = $data['name'];
            $userInfo['nick'] = $data['name'];
            $userInfo['head'] = $data['avatar'];
            return $userInfo;
        } else {
            throw_exception("获取豆瓣用户信息失败：{$data['msg']}");
        }
    }

    //登录成功，获取Github用户信息
    public function github($token){
        $github = ThinkOauth::getInstance('github', $token);
        $data   = $github->call('user');

        if(empty($data['code'])){
            $userInfo['type'] = 'GITHUB';
            $userInfo['name'] = $data['login'];
            $userInfo['nick'] = $data['name'];
            $userInfo['head'] = $data['avatar_url'];
            return $userInfo;
        } else {
            throw_exception("获取Github用户信息失败：{$data}");
        }
    }

    //登录成功，获取Google用户信息
    public function google($token){
        $google = ThinkOauth::getInstance('google', $token);
        $data   = $google->call('userinfo');

        if(!empty($data['id'])){
            $userInfo['type'] = 'GOOGLE';
            $userInfo['name'] = $data['name'];
            $userInfo['nick'] = $data['name'];
            $userInfo['head'] = $data['picture'];
            return $userInfo;
        } else {
            throw_exception("获取Google用户信息失败：{$data}");
        }
    }

    //登录成功，获取Google用户信息
    public function msn($token){
        $msn  = ThinkOauth::getInstance('msn', $token);
        $data = $msn->call('me');

        if(!empty($data['id'])){
            $userInfo['type'] = 'MSN';
            $userInfo['name'] = $data['name'];
            $userInfo['nick'] = $data['name'];
            $userInfo['head'] = '微软暂未提供头像URL，请通过 me/picture 接口下载';
            return $userInfo;
        } else {
            throw_exception("获取msn用户信息失败：{$data}");
        }
    }

    //登录成功，获取点点用户信息
    public function diandian($token){
        $diandian  = ThinkOauth::getInstance('diandian', $token);
        $data      = $diandian->call('user/info');

        if(!empty($data['meta']['status']) && $data['meta']['status'] == 200){
            $userInfo['type'] = 'DIANDIAN';
            $userInfo['name'] = $data['response']['name'];
            $userInfo['nick'] = $data['response']['name'];
            $userInfo['head'] = "https://api.diandian.com/v1/blog/{$data['response']['blogs'][0]['blogUuid']}/avatar/144";
            return $userInfo;
        } else {
            throw_exception("获取点点用户信息失败：{$data}");
        }
    }

    //登录成功，获取淘宝网用户信息
    public function taobao($token){
        $taobao = ThinkOauth::getInstance('taobao', $token);
        $fields = 'user_id,nick,sex,buyer_credit,avatar,has_shop,vip_info';
        $data   = $taobao->call('taobao.user.buyer.get', "fields={$fields}");

        if(!empty($data['user_buyer_get_response']['user'])){
            $user = $data['user_buyer_get_response']['user'];
            $userInfo['type'] = 'TAOBAO';
            $userInfo['name'] = $user['user_id'];
            $userInfo['nick'] = $user['nick'];
            $userInfo['head'] = $user['avatar'];
            return $userInfo;
        } else {
            throw_exception("获取淘宝网用户信息失败：{$data['error_response']['msg']}");
        }
    }

    //登录成功，获取百度用户信息
    public function baidu($token){
        $baidu = ThinkOauth::getInstance('baidu', $token);
        $data  = $baidu->call('passport/users/getLoggedInUser');

        if(!empty($data['uid'])){
            $userInfo['type'] = 'BAIDU';
            $userInfo['name'] = $data['uid'];
            $userInfo['nick'] = $data['uname'];
            $userInfo['head'] = "http://tb.himg.baidu.com/sys/portrait/item/{$data['portrait']}";
            return $userInfo;
        } else {
            throw_exception("获取百度用户信息失败：{$data['error_msg']}");
        }
    }

    //登录成功，获取开心网用户信息
    public function kaixin($token){
        $kaixin = ThinkOauth::getInstance('kaixin', $token);
        $data   = $kaixin->call('users/me');

        if(!empty($data['uid'])){
            $userInfo['type'] = 'KAIXIN';
            $userInfo['name'] = $data['uid'];
            $userInfo['nick'] = $data['name'];
            $userInfo['head'] = $data['logo50'];
            return $userInfo;
        } else {
            throw_exception("获取开心网用户信息失败：{$data['error']}");
        }
    }

    //登录成功，获取搜狐用户信息
    public function sohu($token){
        $sohu = ThinkOauth::getInstance('sohu', $token);
        $data = $sohu->call('i/prv/1/user/get-basic-info');

        if('success' == $data['message'] && !empty($data['data'])){
            $userInfo['type'] = 'SOHU';
            $userInfo['name'] = $data['data']['open_id'];
            $userInfo['nick'] = $data['data']['nick'];
            $userInfo['head'] = $data['data']['icon'];
            return $userInfo;
        } else {
            throw_exception("获取搜狐用户信息失败：{$data['message']}");
        }
    }
}