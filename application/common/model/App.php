<?php

namespace app\common\model;

class App extends \think\Model {

    /**
     * 获取app是否开启
     */
    public static function checkon($appName) {
        $app = self::where('name', $appName)->find();
        if ($app && $app->status && $app->installstate) {
            return true;
        } else {
            return false;
        }
    }

}
