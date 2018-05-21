<?php

namespace app\common\traits\controller;

use think\Container;
use think\exception\HttpResponseException;
use think\Response;

trait Notice
{

    protected function dialog($msg = '', $urls = null, $style = ['color' => 'success', 'icon' => 'fa-check-circle'], array $header = [])
    {
        if ([] == $urls) {
            $urls[0] = ['title' => '返回', 'class' => 'success', 'url' => $_SERVER["HTTP_REFERER"]];
        }
        $result = [
            'msg' => $msg,
            'urls' => $urls,
            'style' => $style
        ];
        $type = $this->getResponseType();
        if ('html' == strtolower($type)) {
            $type = 'jump';
        }
        $response = Response::create($result, $type)->header($header)->options(['jump_template' => Container::get('config')->get('dispatch_dialog_tmpl')]);
        throw new HttpResponseException($response);
    }

}