<?php

// 后台公共文件
if (!function_exists('http_down')) {

    /**
     * 下载远程文件，默认保存在temp下
     * @param  string  $url     网址
     * @param  string  $filename    保存文件名
     * @param  integer $timeout 过期时间
     * @param  bool $repalce 是否覆盖已存在文件
     * @return string 本地文件名
     */
    function http_down($url, $filename = "", $timeout = 60) {
        if (empty($filename)) {
            $filename = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . pathinfo($url, PATHINFO_BASENAME);
        }
        $path = dirname($filename);
        if (!is_dir($path) && !mkdir($path, 0755, true)) {
            return false;
        }
        $url = str_replace(" ", "%20", $url);
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            // curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
            // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            if ('https' == substr($url, 0, 5)) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            }
            $temp = curl_exec($ch);
            if (file_put_contents($filename, $temp) && !curl_error($ch)) {
                return $filename;
            } else {
                return false;
            }
        } else {
            $opts = [
                "http" => [
                    "method" => "GET",
                    "header" => "",
                    "timeout" => $timeout,
                ],
            ];
            $context = stream_context_create($opts);
            if (@copy($url, $filename, $context)) {
                //$http_response_header
                return $filename;
            } else {
                return false;
            }
        }
    }

}
if (!function_exists('get_url_file_ext')) {

    /**
     * 获得url文件拓展名
     * @param  string $url 网址
     * @return string
     */
    function get_url_file_ext($url) {
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        //网址中不存在文件扩展名
        if (empty($ext)) {
            //获取url中的header信息
            $head = get_head($url);
            if (!empty($head)) {
                //从headers中获得文件名
                $headers = explode("\n", $head);
                foreach ($headers as $v) {
                    $item = explode(':', $v);
                    if (count($item) > 1) {
                        $name = strtolower($item[0]);
                        if ($name == 'location') {
                            //302跳转
                            $this->url = count($item) == 2 ? trim($item[1]) : trim($item[1]) . ':' . trim($item[2]); //防止http:被解析
                            $ext = pathinfo($this->url, PATHINFO_EXTENSION);
                            break;
                        } else if ($name == 'content-disposition') {
                            //可能是Content-Disposition: attachment; filename=".$file_name
                            //获得MIME： Content-Type
                            $item[1] = trim($item[1]);
                            $tmps = explode("filename=", $item[1]);
                            $tmp = count($tmps) > 1 ? $tmps[1] : $tmps[0];
                            $ext = pathinfo($tmp, PATHINFO_EXTENSION);
                            break;
                        }
                    }
                }
            }
        }
        return $ext;
    }

}

if (!function_exists('get_head')) {

    /**
     * 获得header
     * @param  string $url 网址
     * @return string
     */
    function get_head($url) {
        $ch = curl_init();
        // 设置请求头, 有时候需要,有时候不用,看请求网址是否有对应的要求
        $header[] = "Content-type: application/x-www-form-urlencoded";
        $user_agent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.146 Safari/537.36";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        // 返回 response_header, 该选项非常重要,如果不为 true, 只会获得响应的正文
        curl_setopt($ch, CURLOPT_HEADER, true);
        // 是否不需要响应的正文,为了节省带宽及时间,在只需要响应头的情况下可以不要正文
        curl_setopt($ch, CURLOPT_NOBODY, true);
        // 使用上面定义的 ua
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 不用 POST 方式请求, 意思就是通过 GET 请求
        curl_setopt($ch, CURLOPT_POST, false);
        $sContent = curl_exec($ch);
        // 获得响应结果里的：头大小
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        // 根据头大小去获取头信息内容
        $header = substr($sContent, 0, $headerSize);
        curl_close($ch);
        return $header;
    }

}

