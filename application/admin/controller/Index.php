<?php

namespace app\admin\controller;

use think\Db;

class Index extends Common {

    public function index() {
        $modelNum = 0;
        $documentNum = 0;
        $columnNum = Db::name('column')->count();
        $attachmentNum = Db::name('attachment')->count();
        $modelList = Db::name('model')->column('table');
        if (!empty($modelList)) {
            foreach ($modelList as $vo) {
                $modelNum++;
                $documentNum+=Db::name($vo)->count();
            }
        }
        $dbinfo = \think\Db::query('SELECT VERSION()');
        $this->assign([
            'modelNum' => $modelNum,
            'documentNum' => $documentNum,
            'columnNum' => $columnNum,
            'attachmentNum' => $attachmentNum,
            'firstModleContent' => $modelList[0],
            'sysinfo' => config('sysinfo'), //系统版本
            'PhpEnv' => phpversion() . '(' . PHP_SAPI . ')', //php环境
            'MysqlVersion' => $dbinfo[0]['VERSION()'], //mysql版本
            'PostMaxSize' => get_cfg_var("post_max_size"), //POST限制
            'UploadMaxFilesize' => get_cfg_var("upload_max_filesize"), //上传限制
            'MemoryLimit' => get_cfg_var("memory_limit"), //内存占用限制
            'MaxExecutionTime' => get_cfg_var("max_execution_time") . '秒', //内存占用限制
        ]);
        return $this->fetch();
    }

    //清空缓存
    public function clear($classify = '') {
        switch ($classify) {
            case 'data':
                if (\think\facade\Cache::clear()) {
                    return true;
                } else {
                    return '数据缓存清空失败';
                }
                break;
            case 'temple':
                if (delFile(ROOT_PATH . 'runtime' . DIRECTORY_SEPARATOR . 'temp')) {
                    return true;
                } else {
                    return '模板缓存清空失败';
                }
                break;
            default:
                break;
        }
    }

    public function userInfo() {
        $uid = session('user_info.uid');
        $gid = session('user_info.groupid');
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['id'] = $uid;
            $result = $this->validate($data, 'AdminUser.updateSelf' . (1 == $gid ? 'Super' : ''));
            if (true !== $result) {
                $this->error($result);
            }
            $AdminUser = model('AdminUser');
            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = $AdminUser->setPassword($data['password']);
            }
            try {
                $allowFields = 1 == $gid ? ['username', 'head_pic', 'realname', 'password', 'mobile', 'email'] : ['head_pic', 'realname', 'password', 'mobile', 'email'];
                $AdminUser->allowField($allowFields)->save($data, ['id' => $uid]);
            } catch (\Exception $ex) {
                return $ex->getMessage();
            }
            $this->success('信息修改成功~', 'admin/index/userInfo');
        } else {
            $info = model('AdminUser')->where('id', $uid)->find();
            $gname = model('AdminRole')->where('id', $info['groupid'])->value('names');
            $this->assign('info', $info);
            $this->assign('gname', $gname);
            $this->assign('ifAcc', 1 == $gid ? true : false);
            return $this->fetch();
        }
    }

}
