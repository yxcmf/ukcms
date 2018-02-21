<?php

namespace app\admin\controller;

use think\Db;
use file\DbFile;

/**
 * 数据库管理
 * @package app\admin\controller
 */
class Database extends Common {

    //备份文件路径
    protected $path = 'data/db/';
    //单卷大小
    protected $PartSize = 20971520;
    //是否压缩备份文件
    protected $compress = 1;
    //压缩级别:1,2,3
    protected $CompressLevel = 3;

    protected function initialize() {
        parent::initialize();
        $this->path = ROOT_PATH . $this->path;
    }

    /**
     * 数据库备份管理
     * @return mixed
     */
    public function index() {
        if ($this->request->isPost()) {
            $data = $this->request->post();
        } else {
            //数据库表
            $tblist = Db::query("SHOW TABLE STATUS");
            $tblist = array_map('array_change_key_case', $tblist);
            $this->assign('tblist', $tblist);
            //备份文件列表
            $this->path = ROOT_PATH . 'data/db/';
            if (!is_dir($this->path)) {
                mkdir($this->path, 0755, true);
            }
            $path = realpath($this->path);
            $flag = \FilesystemIterator::KEY_AS_FILENAME;
            $glob = new \FilesystemIterator($path, $flag);

            $DbFileList = [];
            foreach ($glob as $name => $file) {
                if (preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)) {
                    $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                    $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                    $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                    $part = $name[6];

                    if (isset($DbFileList["{$date} {$time}"])) {
                        $info = $DbFileList["{$date} {$time}"];
                        $info['part'] = max($info['part'], $part);
                        $info['size'] = $info['size'] + $file->getSize();
                    } else {
                        $info['part'] = $part;
                        $info['size'] = $file->getSize();
                    }
                    $extension = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                    $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                    $info['time'] = strtotime("{$date} {$time}");
                    $info['name'] = $info['time'];

                    $DbFileList["{$date} {$time}"] = $info;
                }
            }
            $DbFileList = !empty($DbFileList) ? array_values($DbFileList) : $DbFileList;
            $this->assign('dflist', $DbFileList);
            return $this->fetch();
        }
    }

    /**
     * 备份数据库(参考onethink 麦当苗儿 <zuojiazi@vip.qq.com>)
     * @param null|array $tables 表名
     * @param integer $start 起始行数
     */
    public function export($start = 0) {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (empty($data['tables']) || !is_array($data['tables'])) {
                $this->error('请先选择需要备份的表！');
            }
            $tables = $data['tables'];
            // 初始化
            if (!is_dir($this->path)) {
                mkdir($this->path, 0755, true);
            }

            // 备份配置
            $config = [
                'path' => realpath($this->path) . DIRECTORY_SEPARATOR,
                'part' => $this->PartSize,
                'compress' => $this->compress,
                'level' => $this->CompressLevel,
            ];

            // 检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if (is_file($lock)) {
                $this->error('检测到有一个备份任务正在执行，请稍后再试！');
            } else {
                // 创建锁文件
                file_put_contents($lock, $this->request->time());
            }

            // 检查备份目录是否可写
            is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！');

            // 生成备份文件信息
            $file = [
                'name' => date('Ymd-His', $this->request->time()),
                'part' => 1,
            ];

            // 创建备份文件
            $Database = new DbFile($file, $config);
            if (false !== $Database->create()) {
                // 备份指定表
                foreach ($tables as $table) {
                    $start = $Database->backup($table, $start);
                    while (0 !== $start) {
                        if (false === $start) { // 出错
                            $this->error('备份出错！');
                        }
                        $start = $Database->backup($table, $start[0]);
                    }
                }

                // 备份完成，删除锁定文件
                unlink($lock);
                $this->success('备份完成！');
            } else {
                $this->error('初始化失败，备份文件创建失败！');
            }
        } else {
            $this->error('参数错误~');
        }
    }

    /**
     * 还原数据库(参考onethink 麦当苗儿 <zuojiazi@vip.qq.com>)
     * @param int $time 文件时间戳
     */
    public function import($time = 0) {
        if ($time === 0)
            return '参数错误！';

        // 初始化
        $name = date('Ymd-His', $time) . '-*.sql*';
        $path = realpath($this->path) . DIRECTORY_SEPARATOR . $name;
        $files = glob($path);
        $list = [];
        foreach ($files as $name) {
            $basename = basename($name);
            $match = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
            $gz = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
            $list[$match[6]] = [$match[6], $name, $gz];
        }
        ksort($list);

        // 检测文件正确性
        $last = end($list);
        if (count($list) === $last[0]) {
            foreach ($list as $item) {
                $config = [
                    'path' => realpath($this->path) . DIRECTORY_SEPARATOR,
                    'compress' => $item[2]
                ];
                $Database = new DbFile($item, $config);
                $start = $Database->import(0);

                // 循环导入数据
                while (0 !== $start) {
                    if (false === $start) { // 出错
                        return '还原数据出错！';
                    }
                    $start = $Database->import($start[0]);
                }
            }
            \think\facade\Cache::clear();
            return true;
        } else {
            return '备份文件可能已经损坏，请检查！';
        }
    }

    /**
     * 优化表
     * @param null|string|array $table 表名
     */
    public function optimize($tablename = '') {
        if ($this->request->isPost()) {
            $tableArr = input('post.tables/a');
            if (empty($tableArr)) {
                $this->error('请先选择需要优化的表！');
            }
            $tables = implode('`,`', $tableArr);
            $state = Db::query("OPTIMIZE TABLE `{$tables}`");

            if ($state) {
                $this->success("数据表优化完成！");
            } else {
                $this->error("数据表优化出错请重试！");
            }
        } else {
            if (!empty($tablename)) {
                $state = Db::query("OPTIMIZE TABLE `{$tablename}`");
                if ($state) {
                    return true;
                } else {
                    return "数据表'{$tablename}'优化出错请重试~";
                }
            } else {
                return "参数错误~";
            }
        }
    }

    /**
     * 修复表
     * @param null|string|array $table 表名
     */
    public function repair($tablename = '') {
        if ($this->request->isPost()) {
            $tableArr = input('post.tables/a');
            if (empty($tableArr)) {
                $this->error('请先选择需要修复的表！');
            }
            $tables = implode('`,`', $tableArr);
            $state = Db::query("REPAIR TABLE `{$tables}`");

            if ($state) {
                $this->success("数据表修复完成！");
            } else {
                $this->error("数据表修复出错请重试！");
            }
        } else {
            if (!empty($tablename)) {
                $state = Db::query("REPAIR TABLE `{$tablename}`");
                if ($state) {
                    return true;
                } else {
                    return "数据表'{$tablename}'修复出错请重试~";
                }
            } else {
                return "参数错误~";
            }
        }
    }

    /**
     * 删除备份文件
     * @param int $ids 备份时间
     * @return mixed
     */
    public function delete($time = 0) {
        if ($time == 0)
            return '参数错误！';

        $name = date('Ymd-His', $time) . '-*.sql*';
        $path = realpath($this->path) . DIRECTORY_SEPARATOR . $name;
        array_map("unlink", glob($path));
        if (count(glob($path))) {
            return '备份文件删除失败，请检查权限！';
        } else {
            return true;
        }
    }

}
