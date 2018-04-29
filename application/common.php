<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用公共文件
if (!function_exists('get_client_ip')) {

    /**
     * 获取客户端IP地址
     * @param int $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param bool $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    function get_client_ip($type = 0, $adv = false) {
        $type = $type ? 1 : 0;
        static $ip = NULL;
        if ($ip !== NULL)
            return $ip[$type];
        if ($adv) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos)
                    unset($arr[$pos]);
                $ip = trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

}

if (!function_exists('parse_attr')) {

    /**
     * 解析配置
     * @param string $value 配置值
     * @return array|string
     */
    function parse_attr($value = '') {
        $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
        if (strpos($value, ':')) {
            $value = array();
            foreach ($array as $val) {
                list($k, $v) = explode(':', $val);
                $value[$k] = $v;
            }
        } else {
            $value = $array;
        }
        return $value;
    }

}

if (!function_exists('format_bytes')) {

    /**
     * 格式化字节大小
     * @param  number $size      字节数
     * @param  string $delimiter 数字和单位分隔符
     * @return string            格式化后的带单位的大小
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    function format_bytes($size, $delimiter = '') {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 5; $i++)
            $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }

}
if (!function_exists('isEngine')) {

    function isEngine() {
        if (ini_get('browscap')) {
            $browser = get_browser(NULL, true);
            if ($browser['crawler']) {
                return true;
            }
        } else if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $agent = $_SERVER['HTTP_USER_AGENT'];
            $crawlers = array(
                "/Googlebot/",
                "/Baiduspider/",
                "/Yahoo! Slurp;/",
                "/msnbot/",
                "/Mediapartners-Google/",
                "/Scooter/",
                "/Yahoo-MMCrawler/",
                "/FAST-WebCrawler/",
                "/Yahoo-MMCrawler/",
                "/Yahoo! Slurp/",
                "/FAST-WebCrawler/",
                "/FAST Enterprise Crawler/",
                "/grub-client-/",
                "/MSIECrawler/",
                "/NPBot/",
                "/NameProtect/i",
                "/ZyBorg/i",
                "/worio bot heritrix/i",
                "/Ask Jeeves/",
                "/libwww-perl/i",
                "/Gigabot/i",
                "/bot@bot.bot/i",
                "/SeznamBot/i",
            );
            foreach ($crawlers as $c) {
                if (preg_match($c, $agent)) {
                    return true;
                }
            }
        }
        return false;
    }

}
if (!function_exists('core_message')) {

    function core_message() {
        return base64_decode('Q29weXJpZ2h0IMKpMjAxNy0yMDE4IHd3dy51a2Ntcy5jb20gQWxsIHJpZ2h0cyByZXNlcnZlZC4=');
    }

}
if (!function_exists('dstrpos')) {

    /**
     * 判断$arr中元素字符串是否有出现在$string中
     * @param  $string     $_SERVER['HTTP_USER_AGENT'] 
     * @param  $arr          各中浏览器$_SERVER['HTTP_USER_AGENT']中必定会包含的字符串
     * @param  $returnvalue 返回浏览器名称还是返回布尔值，true为返回浏览器名称，false为返回布尔值【默认】
     * @author           discuz3x
     * @lastmodify    2014-04-09
     */
    function dstrpos($string, $arr, $returnvalue = false) {
        if (empty($string))
            return false;
        foreach ((array) $arr as $v) {
            if (strpos($string, $v) !== false) {
                $return = $returnvalue ? $v : true;
                return $return;
            }
        }
        return false;
    }

}

if (!function_exists('delFile')) {

    function delFile($dirName) {
        if (file_exists($dirName) && $handle = opendir($dirName)) {
            $backreturn = true;
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..") {
                    if (file_exists($dirName . '/' . $item) && is_dir($dirName . '/' . $item)) {
                        delFile($dirName . '/' . $item);
                    } else {
                        if (!unlink($dirName . '/' . $item)) {
                            $backreturn = false;
                        }
                    }
                }
            }
            closedir($handle);
            return $backreturn;
        } else {
            return false;
        }
    }

}
if (!function_exists('paramencode')) {

    function paramencode($arr) {
        $str = '';
        if (!empty($arr)) {
            foreach ($arr as $key => $vo) {
                if (!empty($vo)) {
                    $str.=$key . '=' . $vo . '&';
                }
            }
            $str = substr($str, 0, -1);
        }
        return $str;
    }

}
if (!function_exists('paramdecode')) {

    function paramdecode($str) {
        $arr = [];
        $arr1 = explode('&', $str);
        foreach ($arr1 as $vo) {
            if (!empty($vo)) {
                $arr2 = explode('=', $vo);
                if (!empty($arr2[1])) {
                    $arr[$arr2[0]] = $arr2[1];
                }
            }
        }
        return $arr;
    }

}
if (!function_exists('msubstr')) {

//中文字符串截取
    function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
        switch ($charset) {
            case 'utf-8':$char_len = 3;
                break;
            case 'UTF8':$char_len = 3;
                break;
            default:$char_len = 2;
        }
        //小于指定长度，直接返回
        if (strlen($str) <= ($length * $char_len)) {
            return $str;
        }
        if (function_exists("mb_substr")) {
            $slice = mb_substr($str, $start, $length, $charset);
        } else if (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
        } else {
            $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("", array_slice($match[0], $start, $length));
        }
        if ($suffix)
            return $slice . "…";
        return $slice;
    }

}
if (!function_exists('deletehtml')) {

//去除html js标签
    function deletehtml($document) {
        $document = trim($document);
        if (strlen($document) <= 0) {
            return $document;
        }
        $search = array("'<script[^>]*?>.*?</script>'si", // 去掉 javascript
            "'<[\/\!]*?[^<>]*?>'si", // 去掉 HTML 标记
            "'([\r\n])[\s]+'", // 去掉空白字符
            "'&(quot|#34);'i", // 替换 HTML 实体
            "'&(amp|#38);'i",
            "'&(lt|#60);'i",
            "'&(gt|#62);'i",
            "'&(nbsp|#160);'i"
        );                    // 作为 PHP 代码运行
        $replace = array("",
            "",
            "\\1",
            "\"",
            "&",
            "<",
            ">",
            " "
        );
        return @preg_replace($search, $replace, $document);
    }

}

if (!function_exists('appUrl')) {

    function appUrl($entry = '', $url = '') {
        $entry = 'index' == $entry ? '' : $entry . '.php/';
        if (!empty($url)) {
            $url.= '.' . config('url_html_suffix');
        }
        $urlPath = think\facade\Request::baseFile();
        $urlPath = substr($urlPath, 0, strripos($urlPath, '/') + 1);
        return $urlPath . $entry . $url;
    }

}