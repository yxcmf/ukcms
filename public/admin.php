<?php

namespace think;

if (version_compare(PHP_VERSION, '5.6.0', '<')) {
    header("Content-type: text/html; charset=utf-8");
    die('PHP 5.6.0 及以上版本系统才可运行~ ');
}
define('IF_PUBLIC', true);
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . 'application' . DIRECTORY_SEPARATOR);
define('ROOT_URL', rtrim(dirname($_SERVER["SCRIPT_NAME"]), '\\/') . '/');
if (!is_file(ROOT_PATH . 'config' . DIRECTORY_SEPARATOR . 'database.php')) {
    define('DEFAULT_MODULE', false);
    define('BIND_MODULE', 'install');
} else {
    define('DEFAULT_MODULE', 'admin');
    define('BIND_MODULE', false);
}
// 加载框架引导文件
require ROOT_PATH . 'thinkphp' . DIRECTORY_SEPARATOR . 'base.php';
Container::get('app',array(APP_PATH))->bind(BIND_MODULE)->run()->send();