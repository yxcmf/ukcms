<?php

namespace app\admin\controller;

use think\facade\Cache;

class Link extends Common {

    public function index() {
        $getParam = ['gname' => $this->request->get('gname'), 'keyword' => $this->request->get('keyword')];

        $where = '';
        if (!empty($getParam['gname'])) {
            $where = "group_name='$getParam[gname]'";
        }
        if (!empty($getParam['keyword'])) {
            $where .= '' == $where ? "title like '%$getParam[keyword]%'" : " and title like '%$getParam[keyword]%'";
        }
        $llist = model('Link')->where($where)->order('id desc')->field('id,group_name,title,url,picture,start_time,end_time,orders')->paginate(15, false, [
            'query' => $getParam
        ]);
        if (!empty($llist)) {
            foreach ($llist as &$vo) {
                if ($vo['picture']) {
                    $vo['picpath'] = model('attachment')->getFileInfo($vo['picture']);
                }
            }
        }
        $glist = model('LinkGroup')->order('id desc')->column('name,title');
        $this->assign([
            'llist' => $llist,
            'page' => $llist->render(),
            'glist' => $glist,
        ]);
        return $this->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'Link');
            if (true !== $result) {
                return $this->error($result);
            }
            $Link = model('Link');
            $data['start_time'] = empty($data['start_time']) ? $data['start_time'] : strtotime($data['start_time']);
            $data['end_time'] = empty($data['end_time']) ? $data['end_time'] : strtotime($data['end_time']);
            try {
                $Link->allowField(['group_name', 'title', 'url', 'picture', 'content', 'start_time', 'end_time', 'orders'])->save($data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            Cache::clear('db_link');
            $this->success('链接添加成功~', url('index'));
        } else {
            $glist = model('LinkGroup')->order('id desc')->column('name,title');
            $this->assign('glist', $glist);
            return $this->fetch();
        }
    }

    public function edit($id = 0) {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'Link');
            if (true !== $result) {
                return $this->error($result);
            }
            $data['start_time'] = empty($data['start_time']) ? $data['start_time'] : strtotime($data['start_time']);
            $data['end_time'] = empty($data['end_time']) ? $data['end_time'] : strtotime($data['end_time']);
            $Link = model('Link');
            try {
                $Link->allowField(['group_name', 'title', 'url', 'picture', 'content', 'start_time', 'end_time', 'orders'])->save($data, ['id' => intval($data['id'])]);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            Cache::clear('db_link');
            $this->success('链接编辑成功~', url('index'));
        } else {
            $id = intval($id);
            if ($id < 1) {
                $this->error('参数错误~');
            }
            $info = model('Link')->where('id', $id)->find();
            $info['start_time'] = empty($info['start_time']) ? '' : date('Y-m-d H:m:s', $info['start_time']);
            $info['end_time'] = empty($info['end_time']) ? '' : date('Y-m-d H:m:s', $info['end_time']);
            $this->assign('info', $info);
            $glist = model('LinkGroup')->order('id desc')->column('name,title');
            $this->assign('glist', $glist);
            return $this->fetch();
        }
    }

    public function delete($id = 0) {
        if ($this->request->isPost()) {
            $ids = input('post.ids/a', null, 'intval');
            if (empty($ids)) {
                $this->error('请先勾选需要删除的链接~');
            }
            $Link = model('Link');
            $picIds = $Link->where('id', 'in', $ids)->column('picture');
            try {
                $Link->destroy($ids);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            if ('' != implode('', $picIds)) {
                $Attachment = model('Attachment');
                try {
                    $Attachment->deleteFile($picIds);
                } catch (\Exception $ex) {
                    $this->error($ex->getMessage());
                }
            }
            Cache::clear('db_link');
            $this->success('链接删除成功~');
        } else {
            $id = intval($id);
            if ($id < 1) {
                return '参数错误~';
            }
            $Link = model('Link');
            $linkInfo = $Link->get($id);
            //删除图片
            if ($linkInfo['picture']) {
                $Attachment = model('Attachment');
                try {
                    $Attachment->deleteFile($linkInfo['picture']);
                } catch (\Exception $ex) {
                    return $ex->getMessage();
                }
            }
            try {
                $linkInfo->delete();
            } catch (\Exception $ex) {
                return $ex->getMessage();
            }
            Cache::clear('db_link');
            return true;
        }
    }

    public function changeOrder($id, $num) {
        if (!is_numeric($num) || !is_numeric($id)) {
            return '参数错误';
        }
        if (model('Link')->where('id', $id)->update(['orders' => $num])) {
            Cache::clear('db_link');
            return true;
        } else {
            return '设置排序失败';
        }
    }

}
