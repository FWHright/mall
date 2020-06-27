/**
 * **********************后台操作JS************************
 * ajax 状态显示
 * confirmurl 操作询问
 * showdialog 弹窗表单
 * attachment_icon 附件预览效果
 * preview 预览图片大图
 * cate_select 多级菜单动态加载
 *
 * http://www.yingloujie.com
 * author: andery@foxmail.com
 */
;$(function($){
	//AJAX请求效果
	$('#J_ajax_loading').ajaxStart(function(){
		$(this).show();
	}).ajaxSuccess(function(){
		$(this).hide();
	});

	//确认操作
	$('.J_confirmurl').live('click', function(){
		var self = $(this),
			uri = self.attr('data-uri'),
			acttype = self.attr('data-acttype'),
			title = (self.attr('data-title') != undefined) ? self.attr('data-title') : '提示信息',
			msg = self.attr('data-msg'),
			callback = self.attr('data-callback');
		$.dialog({
			title:title,
			content:msg,
			padding:'10px 20px',
			lock:true,
			ok:function(){
				if(acttype == 'ajax'){
					$.getJSON(uri, function(result){
						if(result.status == 1){
							$.yingloujie.tip({content:result.msg});
							if(callback != undefined){
								eval(callback+'(self)');
							}else{
								window.location.reload();
							}
						}else{
							$.yingloujie.tip({content:result.msg, icon:'error'});
						}
					});
				}else{
					location.href = uri;
				}
			},
			cancel:function(){}
		});
	});

	//弹窗表单
	$('.J_showdialog').live('click', function(){
		var self      = $(this),
			dtitle    = self.attr('data-title'),
			did       = self.attr('data-id'),
			duri      = self.attr('data-uri'),
			dwidth    = parseInt(self.attr('data-width')),
			dheight   = parseInt(self.attr('data-height')),
			dpadding  = (self.attr('data-padding') != undefined) ? self.attr('data-padding') : '',
			dcallback = self.attr('data-callback'),
            dboxtype  = self.attr('data-boxtype'),//文本框类型,用于将弹窗页面的值传回父页面
            dboxid    = self.attr('data-boxname'),//子页面的文本框名称
            dtextid   = self.attr('data-val'),//用于接受传递值的文本框id
            displaypic = self.attr('data-pic'),//是否用于显示图片
            tboxid     = self.attr('data-displaybox'),//显示文本名称的容器名称
            cateurl    = self.attr('data-cateurl'),//是否设置分类获取链接
            cateid     = self.attr('data-cateid'),//获取分类id
            arr       = [],
            zindex    = self.attr('data-zindex'),
            pic       = [];
           var model = 'house';
        //$.dialog({id:did}).close();
		var d = $.dialog({
			id:did,
			title:dtitle,
			width:dwidth ? dwidth : 'auto',
			height:dheight ? dheight : 'auto',
			padding:dpadding,
			lock:true,
            zIndex:zindex ?zindex : 1000,
			ok:function(){
				var info_form = this.dom.content.find('#info_form');
                if(dtextid){
                    //var findmodel = parents.find("input[name='model']").val();
                    //model = findmodel ? findmodel : model;
                    switch(dboxtype){
                        case 'checkbox':
                            this.dom.content.find("input[name='"+dboxid+"']:checked").each(function(i,o){
                                arr.push($(this).val());
                                var me=$(this),id = me.val();
                                    var img     = me.data('img');
                                        title   = me.data('title');
                                    var json_data = {'img':img,'title':title,'id':id};
                                   pic.push(json_data);

                            });

                            var vObj = $("#"+dtextid).val();
                            var arrstr   = arr.join(',');
                            var sv       = '';
                            if(vObj){
                                sv += vObj+arrstr;
                            }else{
                                sv += arrstr;
                            }
                            sv += ',';
                            $("#"+dtextid).val(sv);
                            var str = '';
                            if(pic.length>0){
                                if(displaypic==1){
                                    for(var i in pic){
                                        str += '<li class="left"><img src="'+pic[i]['img']+'" width="100" height="100" /><p>'+pic[i]['title']+'</p><span data="'+pic[i]['id']+'">删除</span></li>';
                                    }
                                }else{
                                    for(var i in pic){
                                        str += '<em>'+pic[i]['title']+'<b data="'+pic[i]['id']+'" class="del">删除</b></em>';
                                    }
                                }

                                $("#"+tboxid).append(str);

                            }
                            break;
                        case 'radio':
                            var me = this.dom.content.find("input[name='"+dboxid+"']:checked");
                            var id = me.val();
                            arr.push(id);
                                var img     = me.data('img');
                                var title   = me.data('title');
                                pic={'img':img,'title':title,'id':id};
                                str = pic['title'];
                            $("#"+tboxid).text(title);
                            $("#"+dtextid).val(id);
                            $("input[name='old_price']").val(me.data('price'));
                            /*if(cateurl){
                                var cate_url = decodeURIComponent(cateurl);
                                cate_url = cate_url.replace('@siteid@',id);
                                $(".J_cate_select").attr("data-uri",cate_url).html('').cate_select('请选择');
                            }*/

                           break;
                        case 'text':
                            arr.push(this.dom.content.find("input[name='"+dboxid+"']").val());
                            break;
                    }

                }

				if(info_form[0] != undefined){
					info_form.submit();
					if(dcallback != undefined){
						eval(dcallback+'()');
					}
					return false;
				}
				if(dcallback != undefined){
					eval(dcallback+'()');
				}

			},
			cancel:function(){}
		});
        var param = cateid ? {'cate':$("#"+cateid).val()} : {};
		$.getJSON(duri,param, function(result){
			if(result.status == 1){
				$.dialog.get(did).content(result.data);
			}else{
                $.dialog.get(did).content(result.msg);
            }
		});
		return false;
	});
//弹窗表单
    $('.J_showmap').live('click', function(){
        var self      = $(this),
            dtitle    = self.attr('data-title'),
            did       = self.attr('data-id'),
            duri      = self.attr('data-uri'),
            dwidth    = parseInt(self.attr('data-width')),
            dheight   = parseInt(self.attr('data-height')),
            dpoint    = $("#map").val();
            dzindex   = self.attr('data-zindex');
            if(dpoint){
                duri += '&mappoint='+dpoint;
            }
        var d = $.dialog({
            id:did,
            title:dtitle,
            width:dwidth ? dwidth : 'auto',
            height:dheight ? dheight : 'auto',
            padding:'',
            lock:true,
            fixed:true,
            zIndex:dzindex ? dzindex : 1000,
            ok:function(){
                   $('#map').val(this.dom.content.find("#mapp").val());
            },
            cancel:function(){}
        });
        $.getJSON(duri, function(result){
            if(result.status == 1){
                $.dialog.get(did).content(result.data);
            }else{
                $.dialog.get(did).content(result.msg);
            }
        });
        return false;
    });
	//附件预览
	$('.J_attachment_icon').live('mouseover', function(){
		var ftype = $(this).attr('file-type');
		var rel = $(this).attr('file-rel');
		switch(ftype){
			case 'image':
				if(!$(this).find('.attachment_tip')[0]){
					$('<div class="attachment_tip"><img src="'+rel+'" /></div>').prependTo($(this)).fadeIn();
				}else{
					$(this).find('.attachment_tip').fadeIn();
				}
				break;
		}
	}).live('mouseout', function(){
		$('.attachment_tip').hide();
	});

	$('.J_attachment_icons').live('mouseover', function(){
		var ftype = $(this).attr('file-type');
		var rel = $(this).attr('file-rel');
		switch(ftype){
			case 'image':
				if(!$(this).find('.attachment_tip')[0]){
					$('<div class="attachment_tip" style="width:160px; height:80px;"><img width="160" height="80" src="'+rel+'" /></div>').prependTo($(this)).fadeIn();
				}else{
					$(this).find('.attachment_tip').fadeIn();
				}
				break;
		}
	}).live('mouseout', function(){
		$('.attachment_tip').hide();
	});


    window.updateAlert = function (text,c) {
        text = text||'default';
        c = c||false;
        var top_alert = $('#top-alert');
        if ( text!='default' ) {
            top_alert.find('.alert-content').text(text);
            if (top_alert.hasClass('block')) {
            } else {
                top_alert.addClass('block').slideDown(200);
                // content.animate({paddingTop:'+=55'},200);
            }
        } else {
            if (top_alert.hasClass('block')) {
                top_alert.removeClass('block').slideUp(200);
                // content.animate({paddingTop:'-=55'},200);
            }
        }
        if ( c!=false ) {
            top_alert.removeClass('alert-error alert-warn alert-info alert-success').addClass(c);
        }
    };

});

//显示大图
;(function($){
	$.fn.preview = function(){
		var w = $(window).width();
		var h = $(window).height();

		$(this).each(function(){
			$(this).hover(function(e){
				if(/.png$|.gif$|.jpg$|.bmp$|.jpeg$/.test($(this).attr("data-bimg"))){
					$("body").append("<div id='preview'><img src='"+$(this).attr('data-bimg')+"' /></div>");
				}
				var show_x = $(this).offset().left + $(this).width();
				var show_y = $(this).offset().top;
				var scroll_y = $(window).scrollTop();
				$("#preview").css({
					position:"absolute",
					padding:"4px",
					border:"1px solid #f3f3f3",
					backgroundColor:"#eeeeee",
					top:show_y + "px",
					left:show_x + "px",
					zIndex:1000
				});
				$("#preview > div").css({
					padding:"5px",
					backgroundColor:"white",
					border:"1px solid #cccccc"
				});
				if (show_y + 230 > h + scroll_y) {
					$("#preview").css("bottom", h - show_y - $(this).height() + "px").css("top", "auto");
				} else {
					$("#preview").css("top", show_y + "px").css("bottom", "auto");
				}
				$("#preview").fadeIn("fast")
			},function(){
				$("#preview").remove();
			})
		});
	};
})(jQuery);

;(function($){
    //联动菜单
    $.fn.cate_select = function(options) {
        var settings = {
            field: 'J_cate_id',
            top_option: '请选择',
            id:'J_cate_select',
            type : 'num',//返数据格式 num返回数字id str 返回父级id和自己id字符串 如;1,2,3
            level : 3//菜单层级 默认获取一级子菜单
        };
        if(options) {
            $.extend(settings, options);
        }

        var self = $(this),
            pid = self.attr('data-pid'),
            uri = self.attr('data-uri'),
            menuid = self.attr('data-menuid');
            selected = self.attr('data-selected'),
            selected_arr = [];
        if(selected != undefined && selected != '0'){
        	if(selected.indexOf('|')){
        		selected_arr = selected.split('|');
        	}else{
        		selected_arr = [selected];
        	}
        }
        self.nextAll('.'+settings.id).remove();
        $('<option value="">--'+settings.top_option+'--</option>').appendTo(self);
        $.getJSON(uri, {id:pid,menuid:menuid}, function(result){
            if(result.status == '1'){
                for(var i=0; i<result.data.length; i++){
                $('<option value="'+result.data[i].id+'">'+result.data[i].name+'</option>').appendTo(self);
                }
            }
            if(selected_arr.length > 0){
            	//IE6 BUG
            	setTimeout(function(){
            		self.find('option[value="'+selected_arr[0]+'"]').attr("selected", true);
	        		self.trigger('change');
            	}, 1);
            }
        });

        var j = 1;
        $('.'+settings.id).die('change').live('change', function(){
            var _this = $(this),
            _pid = _this.val(),
            _menuid = _this.attr('data-menuid');
            _this.nextAll('.'+settings.id).remove();
            if(_pid != ''){
                if($('.'+settings.id).length < settings.level){
                $.getJSON(uri, {id:_pid,menuid:_menuid}, function(result){
                    if(result.status == '1'){
                        var _childs = $('<select class="'+settings.id+' mr10" data-pid="'+_pid+'"><option value="">--'+settings.top_option+'--</option></select>');
                        for(var i=0; i<result.data.length; i++){
                            $('<option value="'+result.data[i].id+'">'+result.data[i].name+'</option>').appendTo(_childs);
                        }
                        _childs.insertAfter(_this);
                        if(selected_arr[j] != undefined){
                        	//IE6 BUG
                        	//setTimeout(function(){
			            		_childs.find('option[value="'+selected_arr[j]+'"]').attr("selected", true);
				        		_childs.trigger('change');
			            	//}, 1);
			            }
                        j++;
                    }
                });}
                //$('#'+settings.field).val(_pid);
                if(settings.type == 'str'){
                    var c = [];
                    _this.prevAll('.'+settings.id).each(function(){
                        c.push($(this).val());
                    });
                    c.push(_pid);
                    _pid = c.sort(sortNumber).join(',');
                }
            }else{
            	_pid = _this.attr('data-pid');
            }
            $('#'+settings.field).val(_pid);
        });
    }
})(jQuery);
function sortNumber(a,b)
{
    return a - b
}
function myCalendar(id){
    WdatePicker({dateFmt:'yyyy-MM-dd',el:id});
}