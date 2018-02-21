<?php

namespace app\admin\model;

use think\helper\Hash;
use think\Db;

/**
 * 后台用户模型
 * @package app\admin\model
 */
class AdminUser extends \think\Model {

    protected $pk = 'id';
//    protected $autoWriteTimestamp = true;
    protected $updateTime = 'last_login_time';

    public function role() {
        return $this->belongsTo('AdminRole', 'groupid', 'id');
    }

    public function setPassword($password) {
        return Hash::make((string) $password);
    }

    protected function checkPassword($password, $codepassword) {
        return Hash::check((string) $password, $codepassword);
    }

    public function login($username = '', $password = '') {
        $username = trim($username);

        // 查找用户
        $user = $this::where('username',$username)->where('status',1)->find();

        if (!$user) {
            throw new \Exception("用户不存在或被禁用！");
        } else {
            // 检查是否分配用户组
            if ($user['groupid'] == 0) {
                throw new \Exception("未分配角色！");
            }
            if (!$this->checkPassword($password, $user->password)) {
                throw new \Exception("密码错误！");
            }
            if (!$user->role->value('status')) {
                throw new \Exception($user->role->value('names') . '角色已被禁用');
            }
            // 更新登录信息
            $user->last_login_time = request()->time();
            $user->last_login_ip = get_client_ip();
            if ($user->save()) {
                // 记录登录SESSION
                $auth = [
                    'uid' => $user->id,
                    'groupid' => $user->groupid,
                    'group_name' => Db::name('admin_role')->where('id', $user->groupid)->value('names'),
                    'username' => $user->username,
                    'realname' => $user->realname,
                    'head_path' => $user->head_pic ? model('attachment')->getFileInfo($user->head_pic) : '',
                    'last_login_time' => $user->last_login_time,
                    'last_login_ip' => get_client_ip(),
                ];
                session('user_info', $auth);
                session('user_sign', $this->dataAuthSign($auth));
                return $user->id;
            } else {
                // 更新登录信息失败
                throw new \Exception("登录信息更新失败，请重新登录！");
            }
        }
    }

    /**
     * 数据签名认证
     */
    public static function dataAuthSign($data = []) {
        // 数据类型检测
        if (!is_array($data)) {
            $data = (array) $data;
        }
        // 排序
        ksort($data);
        // url编码并生成query字符串
        $code = http_build_query($data);
        // 生成签名
        $sign = sha1($code);
        return $sign;
    }

    /**
     * 判断是否登录
     */
    public static function checkLogin() {
        $user = session('user_info');
        if (empty($user)) {
            return false;
        } else {
            return session('user_sign') == self::dataAuthSign($user) ? $user['groupid'] : false;
        }
    }

}
