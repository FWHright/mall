/**
 * Created with JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-11-27
 * Time: 上午8:55
 * To change this template use File | Settings | File Templates.
 */
var uppic = '';
function upmoreimg(edit){
    uppic = UE.getEditor('uploadpic');
    uppic.ready(function(){
        uppic.setDisabled('insertimage');
        uppic.hide();
        //侦听图片上传
        uppic.addListener('beforeinsertimage', function (t, arg) {
            var str = '';
          //  var substr = [];
            for(var i in arg){
                var data = arg[i];
                var title = data['alt'].substring(0,data['alt'].lastIndexOf('.'));
                str += '<li><img src="'+data['src']+'" width="113" height="113" alt="'+data['alt']+'" />';
                str += '<input type="hidden" name="pic[pic][]" value="'+data['src']+'"/>';
                str += '<input type="text" name="pic[alt][]" value="'+title+'" class="imgtitletxt"/>';
                str += '<div class="delbox"><span class="del">删除</span></div></li>';
                // substr.push({'img':data['src'],'alt':data['alt']});
            }
            $("#imageList .list").append(str);

        });

    });
}
function upImage() {
    var myImage = uppic.getDialog("insertimage");
    myImage.open();
}
//删除添加的图片
var delete_pic = [];
$(function(){
    $(".del").live('click',function(){
          var parent = $(this).parent().parent(),id = $(this).data('id');
            delete_pic.push(id);
            $("#delete_pic").val(delete_pic.join(','));//记录删除图片id
            parent.remove();

    });
});
//删除指定下标的数组元素
function arrDel(arr,d){
    return arr.slice(0,d).concat(arr.slice(d+1,arr.length));
}
