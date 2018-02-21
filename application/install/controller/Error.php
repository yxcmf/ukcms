<?php

namespace app\install\controller;

use think\Controller;

class Error extends Controller {

    public function _empty() {
        $this->redirect('index/index');
    }

}
