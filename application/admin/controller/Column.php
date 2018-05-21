<?php

namespace app\admin\controller;

use file\File;
use think\facade\Cache;

/**
 * 栏目控制器
 */
class Column extends Common
{
    protected $contentMenuPid = 57;
    protected $menuPrvUrl = 'admin/content/';
    protected $indexPath = 'home/view/defaults/column/index/';
    protected $contentPath = 'home/view/defaults/column/content/';

    protected function initialize()
    {
        parent::initialize();
        $this->indexPath = APP_PATH . $this->indexPath;
        $this->contentPath = APP_PATH . $this->contentPath;
    }

    public function index()
    {
        $clist = model('Column')->getColumn('id,path,type,title,name,ext_model_id,model_id,cover_picture,url,status,orders');
        $this->assign('clist', $clist);
        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['status'] = isset($data['status']) ? intval($data['status']) : 0;
            $data['type'] = intval($data['type']);
            switch ($data['type']) {
                //单页
                case 1:
                    $fields = ['type', 'path', 'model_id', 'ext_model_id', 'ext_model_id', 'title', 'name', 'meta_title', 'meta_keywords', 'meta_description', 'cover_picture', 'template_list', 'orders', 'status'];
                    $scene = 'page';
                    break;
                //列表
                case 2:
                    $data['listorder'] = 'rand' == $data['listorder'] ? '[' . $data['listorder'] . ']' : $data['listorder'] . ' ' . $data['listorderway'];
                    if (!empty($data['urls'])) {
                        $data['url'] = implode(',', $data['urls']);
                        unset($data['urls']);
                    }
                    $fields = ['type', 'path', 'model_id', 'ext_model_id', 'title', 'name', 'meta_title', 'meta_keywords', 'meta_description', 'cover_picture', 'template_list', 'template_content', 'list_row', 'orders', 'status', 'url', 'listorder'];
                    $scene = 'list';
                    break;
                //链接
                case 3:
                    $fields = ['type', 'path', 'title', 'name', 'cover_picture', 'url', 'orders', 'status'];
                    $scene = 'link';
                    break;
                default:
                    return $this->error('栏目类型错误~');
            }

            $vresult = $this->validate($data, 'Column.' . $scene);
            if (true !== $vresult) {
                return $this->error($vresult);
            }
            $Column = model('Column');
            try {
                $Column->addColumn($fields, $data);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            //更新后台栏目内容管理菜单
            $this->changmenu($data, 'add');
            Cache::clear('db_admin_menu_tree');

            $message = '';
            //栏目拓展字段添加
            if (in_array($data['type'], [1, 2]) && isset($data['modelField']) && $data['ext_model_id']) {
                $data['modelFieldExt'] = isset($data['modelFieldExt']) ? $data['modelFieldExt'] : [];
                $data['modelField']['cname'] = $Column->name;
                $data['modelField']['ifextend'] = 1;
                $ModelField = model('ModelField');
                try {
                    $ModelField->addModelData($data['ext_model_id'], $data['modelField'], $data['modelFieldExt']);
                } catch (\Exception $ex) {
                    $message = '内容：' . $ex->getMessage();
                }
            }
            Cache::clear('db_column');
            $this->success('栏目添加成功~' . $message, url('index'));
        } else {
            $modelList = model('Model')->where('purpose', 'column')->where('status', 1)->order('orders')->column('id,title');
            $columns = model('Column')->getColumn('id,path,title,orders');
            $this->assign('modelList', $modelList);
            $this->assign('columns', $columns);
            $this->assign('template_list', $this->getTempleList($this->indexPath));
            $this->assign('template_content', $this->getTempleList($this->contentPath));
            return $this->fetch();
        }
    }

    public function edit($id = 0)
    {
        $id = intval($id);
        if ($id < 1) {
            $this->error('参数错误~');
        }
        $info = model('Column')->where('id', $id)->find();
        if (empty($info)) {
            $this->error('栏目不存在~');
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['status'] = isset($data['status']) ? intval($data['status']) : 0;
            $data['id'] = $info['id'];
            $data['type'] = intval($data['type']);
            switch ($data['type']) {
                //单页
                case 1:
                    $scene = 'page';
                    $data['model_id'] = 0;
                    $data['template_content'] = '';
                    $data['list_row'] = 0;
                    $data['listorder'] = '';
                    $data['url'] = '';
                    break;
                //列表
                case 2:
                    $data['listorder'] = 'rand' == $data['listorder'] ? '[' . $data['listorder'] . ']' : $data['listorder'] . ' ' . $data['listorderway'];
                    if (!empty($data['urls'])) {
                        $data['url'] = implode(',', $data['urls']);
                        unset($data['urls']);
                    }
                    $scene = 'list';
                    break;
                //链接
                case 3:
                    $scene = 'link';
                    $data['model_id'] = 0;
                    $data['template_content'] = '';
                    $data['list_row'] = 0;
                    $data['listorder'] = '';
                    $data['meta_title'] = '';
                    $data['meta_keywords'] = '';
                    $data['meta_description'] = '';
                    $data['ex_model_id'] = 0;
                    $data['template_list'] = '';
                    break;
                default:
                    return $this->error('栏目类型错误~');
            }
            $vresult = $this->validate($data, 'Column.' . $scene);
            if (true !== $vresult) {
                return $this->error($vresult);
            }

            $Column = model('Column');
            try {
                $Column->editColumn(['type', 'path', 'model_id', 'ext_model_id', 'title', 'name', 'meta_title', 'meta_keywords', 'meta_description', 'cover_picture', 'template_list', 'template_content', 'list_row', 'orders', 'status', 'url', 'listorder'], $data, $info);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $message = '';
            //更新后台栏目内容管理菜单
            $data['oldname'] = $info->name;
            $this->changmenu($data, 'edit');
            Cache::clear('db_admin_menu_tree');
            //栏目拓展字段编辑
            if (in_array($data['type'], [1, 2]) && isset($data['modelField']) && $data['ext_model_id']) {
                $data['modelFieldExt'] = isset($data['modelFieldExt']) ? $data['modelFieldExt'] : [];
                $data['modelField']['cname'] = $data['name'];
                $data['modelField']['ifextend'] = 1;
                $ModelField = model('ModelField');
                if (isset($data['modelField']['id'])) {
                    //编辑
                    try {
                        $ModelField->editModelData($data['ext_model_id'], $data['modelField'], $data['modelFieldExt']);
                    } catch (\Exception $ex) {
                        $message = '栏目拓展内容：' . $ex->getMessage();
                    }
                } else {
                    //新增     
                    try {
                        $ModelField->addModelData($data['ext_model_id'], $data['modelField'], $data['modelFieldExt']);
                    } catch (\Exception $ex) {
                        $message = '栏目拓展内容：' . $ex->getMessage();
                    }
                }
            }
            Cache::clear('db_column');
            $this->success('栏目信息编辑成功~<br>' . $message, url('index'));
        } else {
            $modelList = model('Model')->where('purpose', 'column')->where('status', 1)->order('orders')->column('id,title');
            $columns = model('Column')->getColumn('id,path,title,orders');
            $info['picinfo'] = $info['cover_picture'] ? model('attachment')->getFileInfo($info['cover_picture'], 'name,path,size') : [];
            $info['template_list'] = $info['template_list'] ? $info['template_list'] : 'list';
            $info['template_content'] = $info['template_content'] ? $info['template_content'] : 'content';
            $info['list_row'] = $info['list_row'] ? $info['list_row'] : 10;
            $info['listorder'] = explode(' ', $info['listorder']);
            $this->assign('info', $info);
            $this->assign('modelList', $modelList);
            $this->assign('columns', $columns);
            $this->assign('template_list', $this->getTempleList($this->indexPath));
            $this->assign('template_content', $this->getTempleList($this->contentPath));
            return $this->fetch();
        }
    }

    //ajax获取模型form字段
    public function extfields($mid = 0, $cname = '')
    {
        $mid = intval($mid);
        if ($mid < 1) {
            return '没有指定模型~';
        }
        $fieldList = model('ModelField')->getFieldList($mid, $cname, 'column');
        $this->assign('fieldList', $fieldList);
        return $this->fetch('/inputItem');
    }

    public function addAll()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['status'] = isset($data['status']) ? intval($data['status']) : 0;
            $data['type'] = intval($data['type']);
            switch ($data['type']) {
                //单页
                case 1:
                    if (empty($data['modelinfo'])) {
                        $this->error('栏目名称和标识不能为空');
                    }
                    $infolist = explode("\r\n", $data['modelinfo']);
                    $fields = ['type', 'path', 'model_id', 'ext_model_id', 'title', 'name', 'meta_title', 'meta_keywords', 'meta_description', 'cover_picture', 'template_list', 'orders', 'status'];
                    $scene = 'page';
                    $cfield = 'name';
                    break;
                //列表
                case 2:
                    if (empty($data['modelinfo'])) {
                        $this->error('栏目名称和标识不能为空');
                    }
                    $infolist = explode("\r\n", $data['modelinfo']);
                    $data['listorder'] = 'rand' == $data['listorder'] ? $data['listorder'] . '()' : $data['listorder'] . ' ' . $data['listorderway'];
                    if (!empty($data['urls'])) {
                        $data['url'] = implode(',', $data['urls']);
                        unset($data['urls']);
                    }
                    $fields = ['type', 'path', 'model_id', 'ext_model_id', 'title', 'name', 'meta_title', 'meta_keywords', 'meta_description', 'cover_picture', 'template_list', 'template_content', 'list_row', 'orders', 'status', 'url', 'listorder'];
                    $scene = 'list';
                    $cfield = 'name';
                    break;
                //链接
                case 3:
                    if (empty($data['linkinfo'])) {
                        $this->error('栏目名称和地址不能为空');
                    }
                    $infolist = explode("\r\n", $data['linkinfo']);
                    $fields = ['type', 'path', 'title', 'cover_picture', 'url', 'orders', 'status'];
                    $scene = 'link';
                    $cfield = 'url';
                    break;
                default:
                    return $this->error('栏目类型错误~');
            }
            $Column = model('Column');
            $successNum = 0;
            Cache::clear('db_column');
            foreach ($infolist as $vo) {
                if ($vo) {
                    $colinfo = explode("|", $vo);
                    $data['title'] = $colinfo[0];
                    $data[$cfield] = $colinfo[1];
                    $vresult = $this->validate($data, 'Column.' . $scene);
                    if (true !== $vresult) {
                        $this->error($vresult . '成功添加了' . $successNum . '个栏目~');
                    }
                    try {
                        $Column->allowField($fields)->isUpdate(false)->save($data);
                    } catch (\Exception $ex) {
                        $this->error($ex->getMessage() . '成功添加了' . $successNum . '个栏目~');
                    }
                    $successNum++;
                    //更新后台栏目内容管理菜单
                    $this->changmenu($data, 'add');
                }
            }
            if ($successNum) {
                Cache::clear('db_admin_menu_tree');
            }
            return $this->success('添加成功' . $successNum . '个', url('index'));
        } else {
            $modelList = model('Model')->where('purpose', 'column')->where('status', 1)->order('orders')->column('id,title');
            $columns = model('Column')->getColumn('id,path,title,orders');
            $this->assign([
                'modelList' => $modelList,
                'columns' => $columns,
                'template_list' => $this->getTempleList($this->indexPath),
                'template_content' => $this->getTempleList($this->contentPath)
            ]);
            return $this->fetch();
        }
    }

    public function getOptionField($mid)
    {
        $flist = model('ModelField')->where('model_id', $mid)->where('ifmain', 1)->where('ifeditable', 1)->where('iffixed', 0)->where('type', 'IN', function ($query) {
            $query->name('field_type')->where('ifoption', 1)->field('name');
        })->column('name,title');
        return empty($flist) ? '' : json_encode($flist, true);
    }

    //批量编辑
    public function editAll()
    {
        if (!$this->request->isPost()) {
            $this->error('参数错误~');
        }
        if (input('?post.idstr')) {
            $data = $this->request->post();
            if (empty($data['idstr']) || empty($data['type'])) {
                $this->error('参数错误~');
            }
            $ids = explode(',', $data['idstr']);
            $data['type'] = intval($data['type']);

            if (!in_array($data['type'], [1, 2, 3])) {
                $this->error('栏目类型错误~');
            }
            /*
             * 这里验证器验证数据
             * * */

            $Column = model('Column');
            switch ($data['type']) {
                case 1:
                    $fields = ['path', 'ext_model_id', 'template_list', 'orders', 'status'];
                    $data['model_id'] = 0;
                    $data['template_content'] = '';
                    $data['list_row'] = 0;
                    $data['listorder'] = '';
                    $data['url'] = '';
                    break;
                case 2:
                    if (!empty($data['listorder'])) {
                        $data['listorder'] = 'rand' == $data['listorder'] ? $data['listorder'] . '()' : $data['listorder'] . ' ' . $data['listorderway'];
                    }
                    if (!empty($data['urls'])) {
                        $data['url'] = implode(',', $data['urls']);
                        unset($data['urls']);
                    }
                    $fields = ['path', 'model_id', 'ext_model_id', 'template_list', 'template_content', 'list_row', 'orders', 'status', 'url', 'listorder'];
                    break;
                case 3:
                    $fields = ['path', 'orders', 'status'];
                    $data['name'] = '';
                    $data['model_id'] = 0;
                    $data['template_content'] = '';
                    $data['list_row'] = 0;
                    $data['listorder'] = '';
                    $data['meta_title'] = '';
                    $data['meta_keywords'] = '';
                    $data['meta_description'] = '';
                    $data['ex_model_id'] = 0;
                    $data['template_list'] = '';
                    break;
            }
            foreach ($fields as $value) {
                //去掉不修改的字段
                if ('' == $data[$value]) {
                    unset($data[$value]);
                }
            }
            $clist = $Column->where('id', 'in', $ids)->order('id')->column('id,name,type,model_id,title');
            $message = '';
            foreach ($clist as $value) {
                $data['id'] = $value['id'];
                $data['oldname'] = $value['name'];
                try {
                    $Column->editColumn(['type', 'path', 'model_id', 'ext_model_id', 'template_list', 'template_content', 'list_row', 'orders', 'status', 'url', 'listorder', 'meta_title', 'meta_keywords', 'meta_description'], $data, $value);
                    //更新后台栏目内容管理菜单
                    $this->changmenu($data, 'edit');
                } catch (\Exception $ex) {
                    $message .= $ex->getMessage() . '<br>';
                }
            }
            Cache::clear('db_admin_menu_tree');
            Cache::clear('db_column');
            if ('' == $message) {
                $this->success('栏目编辑成功', url('index'));
            } else {
                $this->error($message, url('index'));
            }
        } else {
            if (!input('?post.ids')) {
                $this->error('请先勾选栏目~');
            }
            $ids = input('post.ids/a', null, 'intval');
            $clist = model('Column')->where('id', 'in', $ids)->order('id')->column('id,title,type');
            $cname = '';
            $type = reset($clist)['type'];
            foreach ($clist as $vo) {
                if ($vo['type'] != $type) {
                    $this->error('栏目类型必须都相同~');
                }
                $type = $vo['type'];
                $cname .= '[' . $vo['title'] . '] ';
            }
            $modelList = model('Model')->where('purpose', 'column')->where('status', 1)->order('orders')->column('id,title');
            $columns = model('Column')->getColumn('id,path,title,orders');
            $this->assign('columns', $columns);
            $this->assign('modelList', $modelList);
            $this->assign('template_list', $this->getTempleList($this->indexPath));
            $this->assign('template_content', $this->getTempleList($this->contentPath));
            $this->assign('idstr', implode(',', $ids));
            $this->assign('type', $type);
            $this->assign('cname', $cname);
            return $this->fetch();
        }
    }

    //批量移动栏目
    public function move()
    {
        if ($this->request->isPost()) {
            $ids = input('post.ids/a', null, 'intval');
            if (empty($ids)) {
                $this->error('请先勾选需要移动的栏目~');
            }
            $cid = intval($this->request->post('cid'));
            if ($cid && in_array($cid, $ids)) {
                $this->error('移动到的上级栏目不能在勾选栏目当中~');
            }

            $Column = model('Column');
            //是否移动到顶级栏目
            if ($cid) {
                $pinfo = $Column->where('id', $cid)->field('id,path')->find();
                if (empty($pinfo)) {
                    $this->error('选择的上级栏目已被删除~');
                }
                $newpath = $pinfo['path'] . $pinfo['id'] . ',';
            } else {
                $newpath = '0,';
            }

            $list = $Column->where('id', 'in', $ids)->order('path desc,orders')->column('id,path,name,title');
            if (empty($list)) {
                $this->error('勾选的栏目已被删除~');
            }
            $message = '';
            $iferror = false;

            Cache::clear('db_column');
            foreach ($list as $vo) {
                try {
                    $Column->moveColumn($newpath, $vo);
                    //更新后台栏目内容管理菜单
                    $vo['path'] = $newpath;
                    $this->changmenu($vo, 'edit');
                } catch (\Exception $ex) {
                    $iferror = true;
                    $message .= $ex->getMessage() . '<br>';
                }
                $message .= '[' . $vo['title'] . ']移动成功<br>';
            }
            Cache::clear('db_admin_menu_tree');
            if ($iferror) {
                $this->error($message);
            } else {
                $this->success($message);
            }
        } else {
            $this->error('提交参数错误~');
        }
    }

    public function delete($id = 0)
    {
        if ($this->request->isPost()) {
            $ids = input('post.ids/a', null, 'intval');
            if (empty($ids)) {
                $this->error('请先勾选需要删除的栏目~');
            }
            $Column = model('Column');
            $list = $Column->where('id', 'in', $ids)->order('path desc,orders')->column('id,name,type,model_id,ext_model_id,title');
            if (empty($list)) {
                $this->error('勾选的栏目已被删除~');
            }
            $message = '';
            $iferror = false;
            foreach ($list as $vo) {
                try {
                    $Column->deleteColumn($vo);
                    //更新后台栏目内容管理菜单
                    $this->changmenu($vo, 'delete');
                } catch (\Exception $ex) {
                    $iferror = true;
                    $message .= $ex->getMessage() . '<br>';
                }
                $message .= '[' . $vo['title'] . ']删除成功<br>';
            }
            Cache::clear('db_admin_menu_tree');
            Cache::clear('db_column');
            if ($iferror) {
                $this->error($message);
            } else {
                $this->success($message);
            }
        } else {
            $id = intval($id);
            if ($id < 1) {
                return '参数错误';
            }
            $Column = model('Column');
            $info = $Column->where('id', $id)->field('id,name,type,model_id,ext_model_id,title')->find();
            try {
                $Column->deleteColumn($info);
                //更新后台栏目内容管理菜单
                $this->changmenu(['name' => $info->name], 'delete');
            } catch (\Exception $ex) {
                return $ex->getMessage();
            }
            Cache::clear('db_admin_menu_tree');
            return true;
        }
    }

    public function changeOrder($id, $num)
    {
        if (!is_numeric($id)) {
            return '参数错误';
        }
        if (!is_numeric($num)) {
            return '排序只能是数字';
        }
        if (model('Column')->where('id', $id)->update(['orders' => $num])) {
            Cache::clear('db_column');
            return true;
        } else {
            return '设置排序失败';
        }
    }

    public function setState($id, $status)
    {
        $id = intval($id);
        $status = intval($status);
        if (($status != 0 && $status != 1) || $id < 0) {
            return '参数错误';
        }
        if (model('Column')->where('id', $id)->update(['status' => $status])) {
            Cache::clear('db_column');
            return true;
        } else {
            return '设置失败';
        }
    }

    //修改后台菜单内容管理下栏目菜单
    protected function changmenu($data, $type)
    {
        switch ($type) {
            case 'add':
                $columnPid = pathToId($data['path']);
                $menuData['pid'] = $columnPid ? $this->getMenuPid($columnPid) : $this->contentMenuPid;
                $menuData['title'] = $data['title'];
                $menuData['icon'] = 'fa fa-sticky-note';
                $menuData['url_type'] = 1;
                $menuData['url_value'] = $this->menuPrvUrl . $data['name'];
                $menuData['url_target'] = '_self';
                $menuData['orders'] = $data['orders'];
                $menuData['ifsystem'] = 1;
                $menuData['ifvisible'] = 1;
                model('adminMenu')->insert($menuData);
                break;
            case 'edit':
                if (isset($data['oldname'])) {
                    $urlValue = $this->menuPrvUrl . $data['oldname'];
                } else {
                    $urlValue = $this->menuPrvUrl . $data['name'];
                    unset($data['name']);
                }

                if (isset($data['path'])) {
                    $columnPid = pathToId($data['path']);
                    $menuData['pid'] = $columnPid ? $this->getMenuPid($columnPid) : $this->contentMenuPid;
                }
                if (isset($data['title'])) {
                    $menuData['title'] = $data['title'];
                }
                if (isset($data['orders'])) {
                    $menuData['orders'] = $data['orders'];
                }
                if (isset($data['name'])) {
                    $menuData['url_value'] = $this->menuPrvUrl . $data['name'];
                }
                model('adminMenu')->where('url_value', $urlValue)->update($menuData);
                break;
            case 'delete':
                $urlValue = $this->menuPrvUrl . $data['name'];
                model('adminMenu')->where('url_value', $urlValue)->delete();
                break;
        }
    }

    //通过栏目id获取后台菜单id
    protected function getMenuPid($columnId)
    {
        $columnName = model('Column')->where('id', $columnId)->value('name');
        return model('adminMenu')->where('url_value', $this->menuPrvUrl . $columnName)->value('id');
    }

    //获取模板信息
    protected function getTempleList($path)
    {
        if (!is_dir($path)) {
            return [];
        }
        $fileList = File::get_dirs($path)['file'];
        if (is_file($path . 'description.php')) {
            $description = require_once $path . 'description.php';
        }
        $tempList = [];
        if (!empty($fileList)) {
            foreach ($fileList as &$vo) {
                $voArr = explode('.', $vo);
                if ('html' == $voArr[1]) {
                    $tempList[$voArr[0]]['name'] = $vo;
                    $tempList[$voArr[0]]['mid'] = isset($description[$voArr[0]]['mid']) ? $description[$voArr[0]]['mid'] : '';
                    $tempList[$voArr[0]]['title'] = isset($description[$voArr[0]]['title']) ? $description[$voArr[0]]['title'] : '';
                }
            }
        }
        return $tempList;
    }

}
