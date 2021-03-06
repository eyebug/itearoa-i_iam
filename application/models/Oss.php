<?php

class OssModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function makeOssKey($dir = "video") {
        $id = 'kpvFToLkzKcom2PS';
        $key = 'dDwpEhEjvjeFWuftGXqp5FDRqyuCxG';
        $host = 'http://iservice.oss-cn-beijing.aliyuncs.com';

        $fileNameList = array(
            'iservicev2',
            $dir,
            date('Ym'),
            md5(time() . mt_rand(1111, 9999))
        );
        $fileName = implode("_", $fileNameList);
        $dir = implode("/", $fileNameList);

        $now = time();
        $expire = 30; // 设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;

        $dtStr = date("c", $end);
        $mydatetime = new DateTime($dtStr);
        $expiration = $mydatetime->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        $expiration = $expiration . "Z";

        // 最大文件大小.用户可以自己设置
        $condition = array(
            0 => 'content-length-range',
            1 => 0,
            2 => 1048576000
        );
        $conditions[] = $condition;

        // 表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(
            0 => 'starts-with',
            1 => '$key',
            2 => $dir
        );
        $conditions[] = $start;

        $arr = array(
            'expiration' => $expiration,
            'conditions' => $conditions
        );
        // echo json_encode($arr);
        // return;
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        $response = array();
        $response['accessid'] = $id;
        $response['host'] = $host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        // 这个参数是设置用户上传指定的前缀
        $response['dir'] = $dir;
        $response['fileName'] = $fileName;
        return $response;
    }
}

?>
