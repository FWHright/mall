<?php /*a:1:{s:49:"G:\phpstudy_pro\WWW\tp6\app\view\login\index.html";i:1576913175;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<title>登录</title>
	<link rel="stylesheet" type="text/css" href="/static/layui/css/layui.css">
	<script type="text/javascript" src="/static/layui/layui.js"></script>
</head>
<body style="background: #1E9FFF">
	<div style="position: absolute; left:50%;top:50%;width: 500px;margin-left: -250px;margin-top: -200px;">
		<div style="background: #ffffff;padding: 20px;border-radius: 4px;box-shadow: 5px 5px 20px #444444;">
			<form class="layui-form">
				<div class="layui-form-item" style="color:gray;">
					<h2><?php echo htmlentities($title); ?>--后台管理系统</h2>
				</div>
				<hr>
				<div class="layui-form-item">
					<label class="layui-form-label">用户名</label>
					<div class="layui-input-block">
						<input type="text" id="account" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">密&nbsp;&nbsp;&nbsp;&nbsp;码</label>
					<div class="layui-input-block">
						<input type="password" id="password" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block">
						<button type="button" class="layui-btn" onclick="dologin()">登录</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<script type="text/javascript">
		layui.use(['layer'],function(){
			$ = layui.jquery;
			layer = layui.layer;
			// 用户名控件获取焦点
			$('#account').focus();
			// 回车登录
			$('input').keydown(function(e){
				if(e.keyCode == 13){
					dologin();
				}
			});
		});
		function dologin(){
			var account = $.trim($('#account').val());
			var pwd = $.trim($('#password').val());
			if(account == ''){
				layer.alert('请输入用户名',{icon:2});
				return;
			}
			if(pwd == ''){
				layer.alert('请输入密码',{icon:2});
				return;
			}
			$.post('/index.php/login/index',{'account':account,'pwd':pwd},function(res){
				if(res.code>0){
					layer.alert(res.msg,{icon:2});
				}else{
					layer.msg(res.msg);
					setTimeout(function(){window.location.href = '/index.php/index/index'},1000);
				}
			},'json');
		}
	</script>
</body>
</html>