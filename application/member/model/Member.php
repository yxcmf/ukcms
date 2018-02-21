<?php

namespace app\member\model;

use think\helper\Hash;

/**
 * 会员模型
 */
class Member extends \think\Model {

    protected $pk = 'id';
    protected $autoWriteTimestamp = true;

    public function role() {
        return $this->belongsTo('MemberGroup', 'groupid', 'id');
    }

    public function setPassword($password) {
        return Hash::make((string) $password);
    }

    protected function checkPassword($password, $codepassword) {
        return Hash::check((string) $password, $codepassword);
    }

    public function login($account = '', $password = '') {
        $account = trim($account);

        // 查找用户
        $user = $this::where('account', $account)->where('status', 1)->find();

        if (!$user) {
            throw new \Exception("用户信息错误或被禁用！");
        } else {
            // 检查是否分配用户组
            if ($user['groupid'] == 0) {
                throw new \Exception("未分配分组！");
            }
            if (!$this->checkPassword($password, $user->password)) {
                throw new \Exception("用户信息错误或被禁用！");
            }
            if (!$user->role->value('status')) {
                throw new \Exception($user->role->value('title') . '会员分组已被禁用');
            }
            // 更新登录信息
            $user->last_login_time = request()->time();
            $user->last_login_ip = get_client_ip();
            if ($user->save()) {
                // 记录登录SESSION
                session('member_acc', $user->account);
                session('member_sign', $this->dataAuthSign($user->account));
            } else {
                // 更新登录信息失败
                throw new \Exception("登录信息更新失败,请重新登录！");
            }
        }
    }

       /**
     * 数据签名认证
     */
    public static function dataAuthSign($data = '') {
        // 生成签名
        $sign = sha1($data);
        return $sign;
    }
     /**
     * 判断是否登录
     */
    public static function checkLogin() {
        $user = session('member_acc');
        if (empty($user)) {
            return false;
        } else {
            return session('member_sign') == self::dataAuthSign($user) ? $user : false;
        }
    }

}
