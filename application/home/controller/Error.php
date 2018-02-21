<?php

namespace app\home\controller;

class Error {

    public function _empty() {
        abort(404, 'home模块控制器不存在~');
    }

}
