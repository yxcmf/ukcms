<?php

/**
 * 系统环境检测
 * @return array 系统环境数据
 */
function check_env() {
    $items = array(
        'os' => array('操作系统', '不限制', '类Unix', PHP_OS, 'success'),
        'php' => array('PHP版本', '5.6', '5.6+', PHP_VERSION, 'success'),
        'upload' => array('附件上传', '不限制', '2M+', '未知', 'success'),
        'gd' => array('GD库', '2.0', '2.0+', '未知', 'success'),
        'disk' => array('磁盘空间', '不限制', '20M', '未知', 'success'),
    );

    //PHP环境检测
    if ($items['php'][3] < $items['php'][1]) {
        $items['php'][4] = 'error';
        session('error', true);
    }

    //附件上传检测
    if (@ini_get('file_uploads'))
        $items['upload'][3] = ini_get('upload_max_filesize');

    //GD库检测
    $tmp = function_exists('gd_info') ? gd_info() : array();
    if (empty($tmp['GD Version'])) {
        $items['gd'][3] = '未安装';
        $items['gd'][4] = 'error';
        session('error', true);
    } else {
        $items['gd'][3] = $tmp['GD Version'];
    }
    unset($tmp);

    //磁盘空间检测
    if (function_exists('disk_free_space')) {
        $items['disk'][3] = floor(disk_free_space(ROOT_PATH) / (1024 * 1024)) . 'M';
    }
    return $items;
}

/**
 * 目录，文件读写检测
 * @return array 检测数据
 */
function check_dirfile() {
    $items = array(
        array('dir', '可写', 'success', './public/uploads'),
        array('dir', '可写', 'success', './runtime'),
        array('dir', '可写', 'success', './config'),
    );

    foreach ($items as &$val) {
        $item = ROOT_PATH . $val[3];
        if ('dir' == $val[0]) {
            if (!is_writable($item)) {
                if (is_dir($item)) {
                    $val[1] = '可读';
                    $val[2] = 'error';
                    session('error', true);
                } else {
                    $val[1] = '不存在';
                    $val[2] = 'error';
                    session('error', true);
                }
            }
        } else {
            if (file_exists($item)) {
                if (!is_writable($item)) {
                    $val[1] = '不可写';
                    $val[2] = 'error';
                    session('error', true);
                }
            } else {
                if (!is_writable(dirname($item))) {
                    $val[1] = '不存在';
                    $val[2] = 'error';
                    session('error', true);
                }
            }
        }
    }

    return $items;
}

/**
 * 函数检测
 * @return array 检测数据
 */
function check_func() {
    $items = array(
        array('pdo', '支持', 'success', '类'),
        array('pdo_mysql', '支持', 'success', '模块'),
        array('file_get_contents', '支持', 'success', '函数'),
        array('mb_strlen', '支持', 'success', '函数'),
        array('finfo_open', '支持', 'success', '函数'),
    );

    foreach ($items as &$val) {
        if (('类' == $val[3] && !class_exists($val[0])) || ('模块' == $val[3] && !extension_loaded($val[0])) || ('函数' == $val[3] && !function_exists($val[0]))
        ) {
            $val[1] = '不支持';
            $val[2] = 'error';
            session('error', true);
        }
    }
    return $items;
}

/**
 * 写入配置文件
 * @param  array $config 配置信息
 */
function write_config($config) {
    if (is_array($config)) {
        //读取配置内容
        $conf = file_get_contents(APP_PATH . 'install/data/conf.tpl');
        //替换配置项
        foreach ($config as $name => $value) {
            $conf = str_replace("[{$name}]", $value, $conf);
        }

        //写入应用配置文件
        $mes = '';
        try {
            file_put_contents(ROOT_PATH . 'config' . DIRECTORY_SEPARATOR . 'database.php', $conf);
        } catch (\Exception $e) {
            $mes = '<p>' . $e->getMessage() . '</p><p>写入配置失败，请复制下面的配置文件内容覆盖到相关的配置文件。</p><p>' . realpath(CONF_PATH) . 'database.php</p>
            <textarea name="" style="width:650px;height:185px">' . $conf . '</textarea>';
            session('error', true);
        }
        if (empty($mes))
            show_msg('配置文件写入成功');
        else
            show_msg($mes, 'error');
    }
}

/**
 * 创建数据表
 * @param  resource $db 数据库连接资源
 */
function create_tables($db, $prefix = '', $acc = []) {
    $sqlfile = session('ifdemo') ? 'installf.sql' : 'installc.sql';
    //读取SQL文件
    $sql = file_get_contents(APP_PATH . 'install/data/' . $sqlfile);
    $sql = str_replace("\r", "\n", $sql);
    $sql = explode(";\n", $sql);

    //替换表前缀
    $sql = str_replace(" `uk_", " `{$prefix}", $sql);
    if(isset($acc['account'])){
        $sql = str_replace("'<account>'", "'$acc[account]'", $sql);
    }
    if(isset($acc['password'])){
        $sql = str_replace("'<password>'", "'$acc[password]'", $sql);
    }

    //开始安装
    show_msg('开始安装数据库...');
    foreach ($sql as $value) {
        $value = trim($value);
        if (empty($value))
            continue;
        $db->execute($value);
        if (substr($value, 0, 12) == 'CREATE TABLE') {
            $name = preg_replace("/^CREATE TABLE `(\w+)` .*/s", "\\1", $value);
            show_msg("创建数据表{$name}" . '...成功');
        }
    }
}

/**
 * 及时显示提示信息
 * @param  string $msg 提示信息
 */
function show_msg($msg, $class = '') {
    @flush();
    echo "<script type=\"text/javascript\">showmsg(\"{$msg}\", \"{$class}\")</script>";
    @ob_flush();
}
