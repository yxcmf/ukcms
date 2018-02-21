<?php

namespace app\admin\controller;

use app\admin\model\Attachment as AttachmentModel;
use think\Image;

/**
 * 附件控制器
 * @package app\admin\controller
 */
class Filemanage extends Common {

    private $uploadUrl = '';
    private $uploadPath = '';

    protected function initialize() {
        parent::initialize();
        $this->uploadUrl = config('public_url') . 'uploads/';
        $this->uploadPath = config('upload_path');
    }

    public function index() {
        //搜索数据验证
        $getParam = ['fileexit' => $this->request->get('fileexit'), 'timestart' => $this->request->get('timestart'), 'timeend' => $this->request->get('timeend')];
        $result = $this->validate($getParam, [
            'fileexit|文件后缀类型' => 'alphaNum',
            'timestart|起始时间' => 'date',
            'timeend|结束时间' => 'date',
        ]);
        if (true !== $result) {
            $this->error($result);
        }
        $where = '';
        if (!empty($getParam['fileexit'])) {
            $where.="ext='$getParam[fileexit]'";
        }
        if (!empty($getParam['timestart'])) {
            $timestart = strtotime($getParam['timestart']);
            $where.='' == $where ? "create_time>='$timestart'" : " and create_time>='$timestart'";
        }
        if (!empty($getParam['timestart'])) {
            $timeend = strtotime($getParam['timeend']);
            $where.='' == $where ? "create_time<='$timeend'" : " and create_time<='$timeend'";
        }
        if (1 != session('user_info.groupid')) {
            $where.='' == $where ? "uid='" . session('user_info.groupid') . "'" : " and uid='" . session('user_info.groupid') . "'";
        }
        // 查询
        $imgExt = config('upload_image_ext');
        $imgExtArr = explode(',', $imgExt);
        // 数据列表
        $list = AttachmentModel::order('orders asc,id desc')->where($where)->paginate(20, false, ['query' => $getParam]);
        foreach ($list as &$value) {
            $value['ext'] = strtolower($value['ext']);
            if (in_array($value['ext'], $imgExtArr)) {
                $value['type'] = 'image';
            } else {
                $value['type'] = 'file';
            }
        }
        // 分页数据 
        $page = $list->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        //系统允许的文件后缀
        $Ext = $imgExt . ',' . config('upload_file_ext');
        $fileExt = explode(',', $Ext);
        $fileExt = array_unique($fileExt);
        $this->assign('fileExt', $fileExt);
        return $this->fetch();
    }

    public function upload($dir = '', $from = '', $module = '', $thumb = 0, $thumbsize = '', $thumbtype = '', $watermark = 1, $sizelimit = -1, $extlimit = '') {
        // 临时取消执行时间限制
        @set_time_limit(0);
        if ($from == 'ueditor') {
            return $this->ueditor();
        }
        if ($dir == '') {
            return $this->error('没有指定上传目录');
        }
        return $this->saveFile($dir, $from, $module, $thumb, $thumbsize, $thumbtype, $watermark, $sizelimit, $extlimit);
    }

    /**
     * 保存附件
     * @param string $dir 附件存放的目录
     * @param string $from 来源
     * @param string $module 来自哪个模块
     * @return string|\think\response\Json
     */
    private function saveFile($dir = '', $from = '', $module = '', $thumb = 0, $thumbsize = '', $thumbtype = '', $watermark = 1, $sizelimit = -1, $extlimit = '') {
        if (!function_exists("finfo_open")) {
            switch ($from) {
                case 'ueditor':
                    return json(['state' => '检测到环境未开启php_fileinfo拓展']);
                default:
                    return json([
                        'status' => 0,
                        'info' => '检测到环境未开启php_fileinfo拓展'
                    ]);
            }
        }
        // 附件大小限制
        $size_limit = $dir == 'images' ? config('upload_image_size') : config('upload_file_size');
        if (-1 != $sizelimit) {
            $sizelimit = intval($sizelimit);
            if ($sizelimit >= 0 && (0 == $size_limit || ($size_limit > 0 && $sizelimit > 0 && $size_limit > $sizelimit))) {
                $size_limit = $sizelimit;
            }
        }
        $size_limit = $size_limit * 1024;
        // 附件类型限制
        $ext_limit = $dir == 'images' ? config('upload_image_ext') : config('upload_file_ext');
        if ('' != $extlimit) {
            $extArr = explode(',', $ext_limit);
            $extArrPara = explode(',', $extlimit);
            $ext_limit = '';
            foreach ($extArrPara as $vo) {
                if (in_array($vo, $extArr) && $vo) {
                    $ext_limit.=$vo . ',';
                }
            }
            if ($ext_limit) {
                $ext_limit = substr($ext_limit, 0, -1);
            }
        }
        $ext_limit = $ext_limit != '' ? parse_attr($ext_limit) : '';
        foreach (['php', 'html', 'htm', 'js'] as $vo) {
            unset($ext_limit[$vo]);
        }
        // 获取附件数据
        switch ($from) {
            case 'ueditor':
                $file_input_name = 'upfile';
                break;
            default:
                $file_input_name = 'file';
        }
        $file = $this->request->file($file_input_name);
        if ($file == null) {
            switch ($from) {
                case 'ueditor':
                    return json(['state' => '获取不到文件信息']);
                default:
                    return json([
                        'status' => 0,
                        'info' => '获取不到文件信息'
                    ]);
            }
        }
        // 判断附件是否已存在
        if ($file_exists = AttachmentModel::get(['md5' => $file->hash('md5')])) {
            switch ($from) {
                case 'ueditor':
                    return json([
                        "state" => "SUCCESS", // 上传状态，上传成功时必须返回"SUCCESS"
                        "url" => $this->uploadUrl . $file_exists['path'], // 返回的地址
                        "title" => $file_exists['name'], // 附件名
                    ]);
                default:
                    //图片已经上传需要缩略图但是没有
                    if (empty($file_exists['thumb']) && 1 == $thumb) {
                        $fileObj = new \think\File($this->uploadPath . DIRECTORY_SEPARATOR . $file_exists['path']);
                        $file_exists->thumb = $this->create_thumb($fileObj, $fileObj->getPathInfo()->getfileName(), $fileObj->getFilename(), $thumbsize, $thumbtype);
                        $file_exists->save();
                    }
                    return json([
                        'status' => 1,
                        'info' => $file_exists['name'] . '已上传过,获取成功~',
                        'id' => $file_exists['id'],
                        'path' => empty($file_exists['thumb']) ? $this->uploadUrl . $file_exists['path'] : $this->uploadUrl . $file_exists['thumb']
                    ]);
            }
        }

        // 判断附件大小是否超过限制
        if ($size_limit > 0 && ($file->getInfo('size') > $size_limit * 1024)) {
            switch ($from) {
                case 'ueditor':
                    return json(['state' => '附件过大']);
                    break;
                default:
                    return json([
                        'status' => 0,
                        'info' => '附件过大'
                    ]);
            }
        }

        // 判断附件格式是否符合
        $file_name = $file->getInfo('name');
        $file_ext = strtolower(substr($file_name, strrpos($file_name, '.') + 1));
        $error_msg = '';
        if ($ext_limit == '') {
            $error_msg = '获取文件后缀限制信息失败！';
        }
        try {
            $fileMine = $file->getMime();
        } catch (\Exception $ex) {
            $error_msg = $ex->getMessage();
        }
        if ($fileMine == 'text/x-php' || $fileMine == 'text/html') {
            $error_msg = '禁止上传非法文件！';
        }
        if (!in_array($file_ext, $ext_limit)) {
            $error_msg = '附件类型不正确！';
        }
        if ($error_msg != '') {
            switch ($from) {
                case 'ueditor':
                    return json(['state' => $error_msg]);
                    break;
                default:
                    return json([
                        'status' => 0,
                        'info' => $error_msg
                    ]);
            }
        }

        // 移动到框架应用根目录指定目录下
        $info = $file->move($this->uploadPath . DIRECTORY_SEPARATOR . $dir);

        if ($info) {
            // 缩略图路径
            $thumb_path_name = '';
            // 生成缩略图
            if ($dir == 'images' && $thumb) {
                $thumb_path_name = $this->create_thumb($info, $info->getPathInfo()->getfileName(), $info->getFilename(), $thumbsize, $thumbtype);
            }

            // 获取附件信息
            $file_info = [
                'uid' => session('user_info.uid'),
                'name' => $file->getInfo('name'),
                'mime' => $file->getInfo('type'),
                'path' => $dir . '/' . str_replace('\\', '/', $info->getSaveName()),
                'ext' => $info->getExtension(),
                'size' => $info->getSize(),
                'md5' => $info->hash('md5'),
                'sha1' => $info->hash('sha1'),
                'thumb' => $thumb_path_name,
                'module' => $module
            ];

            // 水印功能
            $picPath = config('upload_thumb_water_pic')['path'];
            if ($dir == 'images' && $watermark && config('upload_thumb_water') == 1 && $picPath) {
                $this->create_water($info->getRealPath(), $picPath);
            }
            // 写入数据库
            if ($file_add = AttachmentModel::create($file_info)) {
                switch ($from) {
                    case 'ueditor':
                        return json([
                            "state" => "SUCCESS", // 上传状态，上传成功时必须返回"SUCCESS"
                            "url" => $this->uploadUrl . $file_info['path'], // 返回的地址
                            "title" => $file_info['name'], // 附件名
                        ]);
                        break;
                    default:
                        return json([
                            'status' => 1,
                            'info' => $file_info['name'] . '上传成功',
                            'id' => $file_add['id'],
                            'path' => empty($file_info['thumb']) ? $this->uploadUrl . $file_info['path'] : $this->uploadUrl . $file_info['thumb']
                        ]);
                }
            } else {
                switch ($from) {
                    case 'ueditor':
                        return json(['state' => '上传失败']);
                        break;
                    default:
                        return json(['status' => 0, 'info' => '上传成功,写入数据库失败']);
                }
            }
        } else {
            switch ($from) {
                case 'ueditor':
                    return json(['state' => '上传失败']);
                    break;
                default:
                    return json(['status' => 0, 'info' => $file->getError()]);
            }
        }
    }

    /**
     * 创建缩略图
     * @param string $file 目标文件，可以是文件对象或文件路径
     * @param string $dir 保存目录，即目标文件所在的目录名
     * @param string $save_name 缩略图名
     */
    private function create_thumb($file = '', $dir = '', $save_name = '', $thumb_size = '', $thumb_type = '') {
        // 获取要生成的缩略图最大宽度和高度
        $upload_image_thumb = '' == $thumb_size ? config('upload_image_thumb') : $thumb_size;
        $upload_image_thumb_type = '' == $thumb_type ? config('upload_image_thumb_type') : $thumb_type;
        if ($upload_image_thumb == '') {
            $thumb_max_width = 300;
            $thumb_max_height = 300;
        } else {
            list($thumb_max_width, $thumb_max_height) = explode(',', $upload_image_thumb);
        }

        // 读取图片
        $image = Image::open($file);
        // 生成缩略图
        $image->thumb($thumb_max_width, $thumb_max_height, $upload_image_thumb_type);
        // 保存缩略图
        $thumb_path = $this->uploadPath . DIRECTORY_SEPARATOR . 'images/' . $dir . '/thumb/';
        if (!is_dir($thumb_path)) {
            mkdir($thumb_path, 0766, true);
        }
        $thumb_path_name = $thumb_path . $save_name;
        $image->save($thumb_path_name);
        $thumb_path_name = 'images/' . $dir . '/thumb/' . $save_name;
        return $thumb_path_name;
    }

    /**
     * 添加水印
     * @param string $file 要添加水印的文件路径
     */
    private function create_water($file = '', $path = '') {
        $thumb_water_pic = realpath($this->uploadPath . '/' . $path);
        if (file_exists($thumb_water_pic)) {
            // 读取图片
            $image = Image::open($file);
            // 添加水印
            $image->water($thumb_water_pic, config('upload_thumb_water_position')['key'], config('upload_thumb_water_alpha'));
            // 保存水印图片，覆盖原图
            $image->save($file);
        }
    }

    public function setState($id, $status) {
        $id = intval($id);
        $status = intval($status);
        if ($status != 0 && $status != 1)
            return '参数错误';
        if (model('Attachment')->where('id', $id)->update(['status' => $status])) {
            return true;
        } else {
            return '设置失败';
        }
    }

    public function delete($id = '') {
        if ($this->request->isPost()) {
            $ids = input('post.ids/a', null, 'intval');
            if (empty($ids)) {
                $this->error('没有勾选需要删除的文件~');
            }
            $Attachment = model('Attachment');
            try {
                $Attachment->deleteFile($ids);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('文件删除成功~');
        } else {
            $id = intval($id);
            if ($id <= 0) {
                return '参数错误~';
            }
            $Attachment = model('Attachment');
            try {
                $Attachment->deleteFile($id);
            } catch (\Exception $ex) {
                return $ex->getMessage();
            }
            return true;
        }
    }

    /**
     * 处理ueditor上传
     * @return string|\think\response\Json
     */
    private function ueditor() {
        $action = $this->request->get('action');
        switch ($action) {
            /* 获取配置信息 */
            case 'config':
                $config_file = config('static_path') . '/ueditor/config.json';
                $config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents($config_file)), true);
                $result = $config;
                break;

            /* 上传图片 */
            case 'uploadimage':
            /* 上传涂鸦 */
            case 'uploadscrawl':
                return $this->saveFile('images', 'ueditor');
                break;

            /* 上传视频 */
            case 'uploadvideo':
                return $this->saveFile('videos', 'ueditor');
                break;

            /* 上传附件 */
            case 'uploadfile':
                return $this->saveFile('files', 'ueditor');
                break;

            /* 列出图片 */
            case 'listimage':
                return $this->showFileList('listimage');
                break;

            /* 列出附件 */
            case 'listfile':
                return $this->showFileList('listfile');
                break;

            /* 抓取远程附件 */
//            case 'catchimage':
//                $result = include("action_crawler.php");
//                break;

            default:
                $result = ['state' => '请求地址出错'];
                break;
        }

        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                return htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                return json(['state' => 'callback参数不合法']);
            }
        } else {
            return json($result);
        }
    }

    /**
     * @param string $type 类型
     * @param $config
     * @return \think\response\Json
     */
    public function showFileList($type = '') {
        /* 获取参数 */
        $size = input('get.size/d', 0);
        $start = input('get.start/d', 0);
        $allowExit = input('get.exit', '');
        if ($size == 0) {
            $size = 20;
        }
        /* 判断类型 */
        switch ($type) {
            /* 列出附件 */
            case 'listfile':
                $allowExit = '' == $allowExit ? config('upload_file_ext') : $allowExit;
                break;
            /* 列出图片 */
            case 'listimage':
            default:
                $allowExit = '' == $allowExit ? config('upload_image_ext') : $allowExit;
        }

        /* 获取附件列表 */
        $filelist = AttachmentModel::order('orders asc,id desc')->where('ext', 'in', $allowExit)->where('status', 1)->limit($start, $size)->column('id,path,create_time,name,size');
        if (empty($filelist)) {
            return json(array(
                "state" => "没有找到附件",
                "list" => [],
                "start" => $start,
                "total" => 0
            ));
        }
        $uploadUrl = config('public_url');
        $list = [];
        $i = 0;
        foreach ($filelist as $value) {
            $list[$i]['id'] = $value['id'];
            $list[$i]['url'] = $uploadUrl . 'uploads/' . $value['path'];
            $list[$i]['name'] = $value['name'];
            $list[$i]['size'] = format_bytes($value['size']);
            $list[$i]['mtime'] = $value['create_time'];
            $i++;
        }

        /* 返回数据 */
        $result = array(
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => AttachmentModel::where('ext', 'in', $allowExit)->count()
        );
        return json($result);
    }

    /**
     * ajax获取文件信息
     * @param string $ids html代码
     */
    public function ajaxGetFileInfo() {
        $fileInfo = model('attachment')->getFileInfo($this->request->post('ids'), 'id,name,path,size');
        return json($fileInfo);
    }

    /**
     * html代码远程图片本地化
     * @param string $content html代码
     *  @param string $type 文件类型
     */
    public function getUrlFile() {
        $content = $this->request->post('content');
        $type = $this->request->post('type');
        $urls = [];
        preg_match_all("/(src|SRC)=[\"|'| ]{0,}((http|https):\/\/(.*)\.(gif|jpg|jpeg|bmp|png))/isU", $content, $urls);
        $urls = array_unique($urls[2]);

        $path = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
        $file_info = [
            'uid' => session('user_info.uid'),
            'module' => 'admin',
            'thumb' => '',
        ];
        foreach ($urls as $vo) {
            $vo = trim(urldecode($vo));
            $host = parse_url($vo, PHP_URL_HOST);
            if ($host != $_SERVER['HTTP_HOST']) {//当前域名下的文件不下载
                $fileExt = get_url_file_ext($vo);
                $filename = $path . 'temp' . DIRECTORY_SEPARATOR . md5($vo) . '.' . $fileExt;
                if (http_down($vo, $filename) !== false) {
                    $file_info['md5'] = hash_file('md5', $filename);
                    if ($file_exists = AttachmentModel::get(['md5' => $file_info['md5']])) {
                        unlink($filename);
                        $localpath = $this->uploadUrl . $file_exists['path'];
                    } else {
                        $file_info['sha1'] = hash_file('sha1', $filename);
                        $file_info['size'] = filesize($filename);
                        $file_info['mime'] = mime_content_type($filename);

                        $fpath = $type . DIRECTORY_SEPARATOR . date('Ymd');
                        $savePath = $path . $fpath;
                        if (!is_dir($savePath)) {
                            mkdir($savePath, 0755, true);
                        }
                        $fname = DIRECTORY_SEPARATOR . md5(microtime(true)) . '.' . $fileExt;
                        $file_info['name'] = $vo;
                        $file_info['path'] = str_replace(DIRECTORY_SEPARATOR, '/', $fpath . $fname);
                        $file_info['ext'] = $fileExt;

                        if (rename($filename, $savePath . $fname)) {
                            AttachmentModel::create($file_info);
                            $localpath = $this->uploadUrl . $file_info['path'];
                        }
                    }
                    $content = str_replace($vo, $localpath, $content);
                }
            }
        }
        exit($content);
//        return $this->display($content);
    }

}
