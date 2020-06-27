<?php /*a:4:{s:56:"/Users/hanlu/Documents/www/tp6/app/view/index/index.html";i:1576833247;s:56:"/Users/hanlu/Documents/www/tp6/app/view/public/head.html";i:1576732845;s:56:"/Users/hanlu/Documents/www/tp6/app/view/public/left.html";i:1576202895;s:58:"/Users/hanlu/Documents/www/tp6/app/view/public/bottom.html";i:1576662407;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlentities($title); ?>--后台管理系统</title>
    <link rel="stylesheet" type="text/css" href="/static/layui/css/layui.css">
    <script type="text/javascript" src="/static/layui/layui.js"></script>
    <style type="text/css">
        .header{width:100%;height: 50px;line-height: 50px;background: #2e6da4;color:#ffffff;}
        .title{margin-left: 20px;font-size: 20px;}
        .userinfo{float: right;margin-right: 10px;}
        .userinfo a{color:#ffffff;}
        .menu{width: 200px;background:#333744;position:absolute;}
        .main{position: absolute;left:200px;right:0px;}

        .layui-collapse{border:none;}
        .layui-colla-item{border-top:none;}
        .layui-colla-title{background:#42485b;color:#ffffff;}
        .layui-colla-content{border-top:none;padding:0px;}

        .content span{background: #009688;margin-left: 30px;padding: 10px;color:#ffffff;}
        .content div{border-bottom: solid 2px #009688;margin-top: 8px;}
        .content button{float: right;margin-top: -5px;}

        .pagination {
            display: inline-block;
            padding-left: 0;
            margin: 20px 0;
            border-radius: 4px;
        }
        .pagination > li {
            display: inline;
        }
        .pagination > li > a,
        .pagination > li > span {
            position: relative;
            float: left;
            padding: 6px 12px;
            margin-left: -1px;
            line-height: 1.42857143;
            color: #337ab7;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #ddd;
        }
        .pagination > li:first-child > a,
        .pagination > li:first-child > span {
            margin-left: 0;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }
        .pagination > li:last-child > a,
        .pagination > li:last-child > span {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }
        .pagination > li > a:hover,
        .pagination > li > span:hover,
        .pagination > li > a:focus,
        .pagination > li > span:focus {
            z-index: 2;
            color: #23527c;
            background-color: #eee;
            border-color: #ddd;
        }
        .pagination > .active > a,
        .pagination > .active > span,
        .pagination > .active > a:hover,
        .pagination > .active > span:hover,
        .pagination > .active > a:focus,
        .pagination > .active > span:focus {
            z-index: 3;
            color: #fff;
            cursor: default;
            background-color: #337ab7;
            border-color: #337ab7;
        }
        .pagination > .disabled > span,
        .pagination > .disabled > span:hover,
        .pagination > .disabled > span:focus,
        .pagination > .disabled > a,
        .pagination > .disabled > a:hover,
        .pagination > .disabled > a:focus {
            color: #777;
            cursor: not-allowed;
            background-color: #fff;
            border-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <span class="title"><span style="font-size: 20px;"><?php echo htmlentities($title); ?></span>--后台管理系统</span>
        <span class="userinfo">【<?php echo htmlentities($login); ?>】<span><a href="javascript:;">退出</a></span></span>
    </div>
<div class="menu" id="menu">
    <div class="layui-collapse" lay-accordion>
        <?php foreach($left as $k=>$left_v): ?>
            <div class="layui-colla-item">
                <h2 class="layui-colla-title"><?php echo htmlentities($left_v['title']); ?></h2>
                <div class="layui-colla-content <?php if($k==0): ?>layui-show<?php endif; ?>">
                    <ul class="layui-nav layui-nav-tree">
                        <?php foreach($left_v['lists'] as $lists_v): ?>
                            <li class="layui-nav-item"><a href="index.html"><?php echo htmlentities($lists_v['title']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="main" style="padding:10px;">
    <div class="content">
        <span>商品列表</span>
        <button class="layui-btn layui-btn-sm" onclick="add()">添加</button>
        <div></div>
    </div>
    <form class="layui-form">
        <div class="layui-form-item" style="margin-top:10px;">
            <div class="layui-input-inline">
                <select name="status">
                    <option value="0" <?php if($status==0): ?>selected<?php endif; ?>>全部</option>
                    <option value="1" <?php if($status==1): ?>selected<?php endif; ?>>开启</option>
                    <option value="2" <?php if($status==2): ?>selected<?php endif; ?>>关闭</option>
                </select>
            </div>
            <button class="layui-btn layui-btn-primary"><i class="layui-icon">&#xe615;</i>搜索</button>
        </div>
    </form>
    <table class="layui-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>商品标题</th>
                <th>分类</th>
                <th>原价</th>
                <th>折扣</th>
                <th>现价</th>
                <th>库存</th>
                <th>状态</th>
                <th>添加时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($right) || $right instanceof \think\Collection || $right instanceof \think\Paginator): $i = 0; $__LIST__ = $right;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$right_v): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><?php echo htmlentities($right_v['id']); ?></td>
                    <td><?php echo htmlentities($right_v['title']); ?></td>
                    <td><?php echo htmlentities($right_v['cat']); ?></td>
                    <td><?php echo htmlentities($right_v['price']); ?></td>
                    <td><?php echo htmlentities($right_v['discount']); ?></td>
                    <td>
                        <?php if($right_v['discount']!=0): ?>
                            <?php echo htmlentities($right_v['price']*($right_v['discount']/10)); else: ?>
                            <?php echo htmlentities($right_v['price']); ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlentities($right_v['stock']); ?></td>
                    <td><?php echo htmlentities($right_v['status']); ?></td>
                    <td><?php echo htmlentities($right_v['add_time']); ?></td>
                    <td>
                        <button class="layui-btn layui-btn-xs" onclick="edit(<?php echo htmlentities($right_v['id']); ?>)">编辑</button>
                        <button class="layui-btn layui-btn-danger layui-btn-xs" onclick="del(<?php echo htmlentities($right_v['id']); ?>)">删除</button>
                    </td>
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="layui-box layui-laypage layui-laypage-default">
        <a href="/index.php/Index/index?p=<?php echo htmlentities($p-1); ?>&status=<?php echo htmlentities($status); ?>" class="layui-laypage-prev <?php if($p<=1): ?>layui-disabled<?php endif; ?>">上一页</a>
        <?php $__FOR_START_1046108583__=0;$__FOR_END_1046108583__=$count;for($i=$__FOR_START_1046108583__;$i < $__FOR_END_1046108583__;$i+=1){ if($p == $i+1): ?>
                <span class="layui-laypage-curr">
                    <em class="layui-laypage-em"></em>
                    <em><?php echo htmlentities($i+1); ?></em>
                </span>
            <?php else: ?>
                <a href="/index.php/Index/index?p=<?php echo htmlentities($i+1); ?>&status=<?php echo htmlentities($status); ?>"><?php echo htmlentities($i+1); ?></a>
            <?php endif; } ?>
        <a href="/index.php/Index/index?p=<?php echo htmlentities($p+1); ?>&status=<?php echo htmlentities($status); ?>" class="layui-laypage-next <?php if($p>=$count): ?>layui-disabled<?php endif; ?>">下一页</a>
    </div>
</div>
<script type="text/javascript">
    function add(){
        layer.open({
            type: 2,
            title: '添加',
            shade: 0.3,
            area: ['480px', '440px'],
            content: '/index.php/index/add'
        });
    }
    function edit(id){
        layer.open({
            type: 2,
            title: '添加',
            shade: 0.3,
            area: ['480px', '440px'],
            content: '/index.php/index/edit?id='+id
        });
    }
    function del(id){
        layer.confirm('确定要删除吗？', {
            icon:3,
            btn: ['确定','取消']
        }, function(){
            $.post('/index.php/index/del',{'id':id},function(res){
                if(res.code>0){
                    layer.alert(res.msg,{icon:2});
                }else{
                    layer.msg(res.msg);
                    setTimeout(function(){window.location.reload();},1000);
                }
            },'json');
        });
    }
</script>
</body>
</html>
<script>
    layui.use(['element','layer','laypage','layedit'], function(){
        var element = layui.element;
        var laypage = layui.laypage;
        $ = layui.jquery;
        form = layui.form;
        layedit = layui.layedit;
        resetMenuHeight();
    });
    // 重新设置菜单容器高度
    function resetMenuHeight(){
        var height = document.documentElement.clientHeight - 50;
        $('#menu').height(height);
    }
</script>