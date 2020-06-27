<?php
/**
 * Created by PhpStorm.
 * User: junyv
 * Date: 2016/10/7
 * Time: 17:46
 */

namespace org;

/**
 * Class Avatar
 * @package org
 * 上传用户头像
 */
class Avatar
{
    public $data = [];
    private $uid;
    private $avatardata;
    private $no_avatar = '/static/images/noavatar.jpg';
    private $dir = './uploads/avatar/';
    public function upload(){
        $this->getData();
        //根据用户id创建文件夹
        if(isset($this->data['uid']) && isset($this->data['avatardata'])) {
            $this->uid = intval($this->data['uid']);
            $this->avatardata = $this->data['avatardata'];
        } else {
            $return['status'] = 0;
            echo json_encode($return);exit;
        }
        $path = $this->dir.$this->uid.'/';
        if(!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        //存储flashpost图片
        $filename = $path.'180x180.jpg';
        $fp = fopen($filename, 'w');
        fwrite($fp, $this->avatardata);
        fclose($fp);
        $smallpath = $path.'30x30.jpg';
        $smallpath_45 = $path.'45x45.jpg';
        $smallpath_90 = $path.'90x90.jpg';
        \think\Image::open($filename)->thumb(30,30)->save($smallpath);
        \think\Image::open($filename)->thumb(45,45)->save($smallpath_45);
        \think\Image::open($filename)->thumb(90,90)->save($smallpath_90);
        $return['status'] = 1;
        $return['data']   = [180=>$filename,90=>$smallpath_90,45=>$smallpath_45,30=>$smallpath];
        echo json_encode($return);exit;
    }

    /**
     * @param int $uid 会员id
     * @return array|string
     * 根据id返回用户头像
     */
    public function getAvatar($uid = 0){
        $data  = [];
        if($uid){
            $path = $this->dir.$uid.'/';
            $files = glob($path."*");
            foreach($files as $_files) {
                if(is_file($_files)){
                    $info = @getimagesize($_files);
                    $data[$info[0]] = config('domain.pic').str_replace('./','/',$_files);
                }
            }
            krsort($data);
            $data = $data ? $data : $this->no_avatar;
        }else{
            $data = $this->no_avatar;
        }
        return $data;
    }
    public function getData(){
        $postStr = file_get_contents("php://input");
        if($postStr) {
            $this->data['avatardata'] = $postStr;
        }
    }
}