<?php

namespace app\admin\controller;

class Domain extends Common {

    public function index() {
        //搜索数据验证
        $getParam = ['keyword' => $this->request->get('keyword')];
        $result = $this->validate($getParam, [
            'keyword|域名搜索关键词' => 'regex:^[a-z-A-Z\d\.]*$',
        ]);
        if (true !== $result) {
            $this->error($result);
        }
        if ($getParam['keyword']) {
            $list = model('Domain')->where('name', 'like', "%$getParam[keyword]%")->order('id desc')->paginate(20, false, [
                'query' => $getParam
            ]);
        } else {
            $list = model('Domain')->order('id desc')->paginate(20);
        }
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['status'] = isset($data['status']) ? 1 : 0;
            $data['addtype'] = intval($data['addtype']);
            if (1 == $data['addtype']) {
                $result = $this->validate($data, 'Domain');
                if (true !== $result) {
                    return $this->error($result);
                }
                $Domain = model('Domain');
                try {
                    $Domain->allowField(['name', 'view_directory', 'title', 'status'])->save($data);
                } catch (\Exception $ex) {
                    return $this->error($ex->getMessage());
                }
                cache($this->request->host(), null);
                return $this->success('域名添加成功~', url('index'));
            } else {
                //批量添加
                if (empty($data['colinfo'])) {
                    return $this->error('域名和目录必须填写');
                }
                $collist = explode("\r\n", $data['colinfo']);
                $errorNum = 0;
                $successNum = 0;
                $allData = [];
                foreach ($collist as $vo) {
                    if ($vo) {
                        $colinfo = explode("|", $vo);
                        $data['name'] = $colinfo[0];
                        $data['view_directory'] = $colinfo[1];
                        $vresult = $this->validate($data, 'Domain.batch');
                        if (true !== $vresult) {
                            $errorNum++;
                        } else {
                            $allData[] = $data;
                            $successNum++;
                        }
                    }
                }
                if ([] == $allData) {
                    return $this->error('域名和目录格式错误');
                } else {
                    if (model('Domain')->saveAll($allData)) {
                        cache($this->request->host(), null);
                        return $this->success('添加成功' . $successNum . '个,失败' . $errorNum . '个~', url('index'));
                    } else {
                        return $this->error('批量添加域名失败~');
                    }
                }
            }
        } else {
            return $this->fetch();
        }
    }

    public function edit($id = 0) {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['id'] = intval($data['id']);
            $result = $this->validate($data, 'Domain');
            if (true !== $result) {
                return $this->error($result);
            }
            $data['status'] = isset($data['status'])? : 0;
            $Domain = model('Domain');
            try {
                $Domain->update($data, [], ['name', 'view_directory', 'title', 'status']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            cache($this->request->host(), null);
            $this->success('域名编辑成功~', url('index'));
        } else {
            $id = intval($id);
            if ($id < 1) {
                $this->error('参数错误~');
            }
            $info = model('Domain')->where('id', $id)->find();
            $this->assign('info', $info);
            return $this->fetch();
        }
    }

    public function setState($id, $status) {
        $id = intval($id);
        $status = intval($status);
        if (($status != 0 && $status != 1) || $id < 0) {
            return '参数错误';
        }
        if (model('Domain')->where('id', $id)->update(['status' => $status])) {
            cache($this->request->host(), null);
            return true;
        } else {
            return '设置失败';
        }
    }

    public function delete($id = 0) {
        if ($this->request->isPost()) {
            $ids = input('post.ids/a', null, 'intval');
            if (empty($ids)) {
                return $this->error('请先勾选需要删除的域名~');
            }
            if (model('Domain')->destroy($ids)) {
                cache($this->request->host(), null);
                return $this->success('域名删除成功~');
            } else {
                return $this->error('域名删除失败~');
            }
        } else {
            $id = intval($id);
            if (model('Domain')->destroy($id)) {
                return true;
            } else {
                return '数据库操作失败';
            }
        }
    }

}
