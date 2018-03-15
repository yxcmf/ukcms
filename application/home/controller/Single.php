<?php

namespace app\home\controller;

use think\Db;

class Single extends Common {

    //独立模型内容列表
    public function index($id = '') {
        $result = $this->validate(['modelId' => $id], ['modelId|模型ID' => 'require|number']);
        if (true !== $result) {
            $this->error($result);
        }
        //模型用途检查
        $info = Db::name('Model')->where('id', $id)->find();
        if ('column' == $info['purpose']) {
            $this->error('不可以是栏目模型');
        }

        if ($this->request->isPost()) {
            if (!$info['ifsub']) {
                $this->error($info['title'] . '模型禁止投稿~');
            }
            $data = $this->request->post();
            // 验证码
            if (config('captcha_signin_model')) {
                $captcha = $data['captcha'];
                $captcha == '' && $this->error('请输入验证码');
                if (!captcha_check($captcha, '', config('captcha'))) {
                    //验证失败
                    $this->error('验证码错误或失效');
                }
            }
            //令牌验证
            $vresult = $this->validate($data, ['__token__|令牌' => 'require|token']);
            if (true !== $vresult) {
                $this->error($vresult);
            }
            $ModelField = model('ModelField');
            //后置审核
            $data['modelField']['status'] = 0;
            $data['modelFieldExt'] = isset($data['modelFieldExt']) ? $data['modelFieldExt'] : [];
            try {
                $result = $ModelField->addModelData($info['id'], $data['modelField'], $data['modelFieldExt']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('添加成功~');
        } else {
            $list = model('ModelField')->getDataList($info['table'], "status='1'", "*", "", "orders,id desc", "", 15);
            $this->assign('modelTitle', $info['title']);
            $this->assign('list', $list);
            $this->assign('page', $list->render());
            if ($info['ifsub']) {
                $fieldList = model('ModelField')->getFieldList($info['id']);
                $this->assign('fieldList', $fieldList);
            }
            return $this->display('single/index');
        }
    }

    //独立内容
    public function content($mid = '', $id = 0) {
        $result = $this->validate(['modelId' => $mid, 'id' => $id], ['modelId|模型ID' => 'require|number', 'id|文档ID' => 'require|number']);
        if (true !== $result) {
            $this->error($result);
        }
        //模型用途检查
        $info = Db::name('Model')->where('id', $mid)->find();
        if ('column' == $info['purpose']) {
            $this->error('不可以显示栏目模型');
        }
        //内容所有字段
        $data = model('ModelField')->getDataInfo($info['table'], "id='" . $id . "' and  cname='" . $name . "' and status='1'", '*', '*');
        if (empty($data)) {
            $this->error('内容不存在或未审核~');
        }
        $this->assign('data', $data);
        return $this->display('single/content');
    }

}
