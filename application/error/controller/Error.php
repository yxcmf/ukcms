<?php

namespace app\error\controller;

class Error {

    public function _empty() {
        abort(404, '应用模块不存在~');
    }

}
