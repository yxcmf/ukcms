<?php

namespace app\admin\model;

/**
 * 附件模型
 * @package app\admin\model
 */
class Attachment extends \app\common\model\Attachment {

    public function deleteFile($id) {
        $path = config('upload_path');
        $groupid = session('user_info.groupid');
        $uid = session('user_info.uid');
        if (is_array($id)) {
            $files_path = 1 == $groupid ? self::where('id', 'in', $id)->column('path,thumb', 'id') : self::where('id', 'in', $id)->where('uid', $uid)->column('path,thumb', 'id');
            if (!empty($files_path)) {
                $mes = '';
                $id = [];
                foreach ($files_path as $key => $value) {
                    $real_path = realpath($path . '/' . $value['path']);
                    $real_path_thumb = realpath($path . '/' . $value['thumb']);

                    if (is_file($real_path) && !unlink($real_path)) {
                        $mes.="删除" . $real_path . "失败，";
                    }
                    if (is_file($real_path_thumb) && !unlink($real_path_thumb)) {
                        $mes.="删除" . $real_path_thumb . "失败，";
                    }
                    $id[] = $key;
                }
                self::where('id', 'in', $id)->delete();
                if ('' != $mes) {
                    throw new \Exception($mes);
                }
            } else {
                throw new \Exception(1 == $groupid ? "文件数据库记录已不存在~" : "没有权限删除别人上传的附件~");
            }
        } else {
            $file_path = 1 == $groupid ? self::where('id', $id)->field('path,thumb')->find() : self::where('id', $id)->where('uid', $uid)->field('path,thumb')->find();
            if (isset($file_path['path'])) {
                $real_path = realpath($path . '/' . $file_path['path']);
                $real_path_thumb = realpath($path . '/' . $file_path['thumb']);

                if (is_file($real_path) && !unlink($real_path)) {
                    throw new \Exception("删除" . $real_path . "失败");
                }
                if (is_file($real_path_thumb) && !unlink($real_path_thumb)) {
                    throw new \Exception("删除" . $real_path_thumb . "失败");
                }
                self::where('id', $id)->delete();
            } else {
                throw new \Exception(1 == $groupid ? "文件数据库记录已不存在~" : "没有权限删除别人上传的附件~");
            }
        }
    }

}
