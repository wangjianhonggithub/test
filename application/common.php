<?php
// 应用公共文件
\think\facade\Url::root('/index.php?s=');

/**
 * 获取当前域名及根路径
 * @return string
 */
function base_url()
{
    $request = Request::instance();
    $subDir = str_replace('\\', '/', dirname($request->server('PHP_SELF')));
    return $request->scheme() . '://' . $request->host() . $subDir . ($subDir === '/' ? '' : '/');
}
/**
 * 生成密码hash值
 * @param $password
 * @return string
 */
function dzcpass_shift($password){
    return md5(md5($password) . 'dzc_hejiaz_password');
}
/**
 * 驼峰命名转下划线命名
 * @param $str
 * @return string
 */
function toUnderScore($str){
    $dstr = preg_replace_callback('/([A-Z]+)/', function ($matchs) {
        return '_' . strtolower($matchs[0]);
    }, $str);
    return trim(preg_replace('/_{2,}/', '_', $dstr), '_');
}
/**
 * curl请求指定url (get)
 * @param $url
 * @param array $data
 * @return mixed
 */
function curl($url, $data = [])
{
    // 处理get数据
    if (!empty($data)) {
        $url = $url . '?' . http_build_query($data);
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

/**
 * curl请求指定url (post)
 * @param $url
 * @param array $data
 * @return mixed
 */
function curlPost($url, $data = [])
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 * 数据导出到excel(csv文件)
 * @param $fileName
 * @param array $tileArray
 * @param array $dataArray
 */
 function export_excel($fileName,$tileArray = [],$dataArray = []){
    $file_name = "order-".(date('Ymdhis',time())).".csv";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename='.$file_name );
    header('Cache-Control: max-age=0');
    $file = fopen('php://output',"a");
    $limit = 1000;
    $calc = 0;
    foreach ($tileArray as $v){
        $tit[] = iconv('UTF-8', 'GB2312//IGNORE',$v);
    }
    fputcsv($file,$tit);
    foreach ($dataArray as $v){
        $calc++;
        if($limit == $calc){
            ob_flush();
            flush();
            $calc = 0;
        }
        foreach($v as $t){
            $tarr[] = iconv('UTF-8', 'GB2312//IGNORE',$t);
        }
        fputcsv($file,$tarr);
        unset($tarr);
    }
    unset($list);
    fclose($file);
    exit();
}

function tab1ss($tab1s){
    $tab=explode('-',$tab1s);
    return "充值满".$tab[0].',减'.$tab[1];
}
		
function coupon($coupon){
        $str='<select name="prom[expression]">';
    foreach($coupon as $k=>$v){
        $str.='<option value="'.$v['coupon_id'].'">'.$v['name'].'</option>';
    }
    $str.='</select>';

    return $str;
    
}
	
function catname($cat_id){
    return db('category')->where(array('id'=>$cat_id))->value('name');
}
	
/**
 * 多维数组合并
 * @param $array1
 * @param $array2
 * @return array
 */
function array_merge_multiple($array1, $array2)
{
	
    $merge = $array1 + $array2;
    $data = [];
    foreach ($merge as $key => $val) {
        if (
            isset($array1[$key])
            && is_array($array1[$key])
            && isset($array2[$key])
            && is_array($array2[$key])
        ) {
            $data[$key] = array_merge_multiple($array1[$key], $array2[$key]);
        } else {
            $data[$key] = isset($array2[$key]) ? $array2[$key] : $array1[$key];
        }
    }
    return $data;
}

/**
 * 写入日志
 * @param string|array $values
 * @param string $dir
 * @return bool|int
 */
function write_log($values, $dir)
{
    if (is_array($values))
        $values = print_r($values, true);
    // 日志内容
    $content = '[' . date('Y-m-d H:i:s') . ']' . PHP_EOL . $values . PHP_EOL . PHP_EOL;
    try {
        // 文件路径
        $filePath = $dir . '/logs/';
        // 路径不存在则创建
        !is_dir($filePath) && mkdir($filePath, 0755, true);
        // 写入文件
        return file_put_contents($filePath . date('Ymd') . '.log', $content, FILE_APPEND);
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * 写入日志 (使用tp自带驱动记录到runtime目录中)
 * @param $value
 * @param string $type
 * @return bool
 */
function log_write($value, $type = 'wymall-info')
{
    $msg = is_string($value) ? $value : print_r($value, true);
    return Log::write($msg, $type);
}

/**
 * 二维数组排序
 * @param $arr
 * @param $keys
 * @param bool $desc
 * @return mixed
 */
function array_sort($arr, $keys, $desc = false)
{
    $key_value = $new_array = array();
    foreach ($arr as $k => $v) {
        $key_value[$k] = $v[$keys];
    }
    if ($desc) {
        arsort($key_value);
    } else {
        asort($key_value);
    }
    reset($key_value);
    foreach ($key_value as $k => $v) {
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}

// ajax公共返回函数
function ajax($code=200,$mess='',$data=''){
    return compact('code', 'mess', 'data');
}

function echojson($code=200,$mess='',$data=''){
    return json_encode(['code' => $code, 'mess' => $mess, 'data' =>$data]);
} 

// 生成唯一标示
function getonlynum($stype = 8, $prefix = ''){
    // 生成一个唯一编号  生成唯一标示
    $yCode = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 0);

    switch ($stype) {
        case 6:
            // 6位
            $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $rand = $code[rand(0, 25)]
                . strtoupper(dechex(date('m')))
                . date('d')
                . substr(time(), -5)
                . substr(microtime(), 2, 5)
                . sprintf('%02d', rand(0, 99));

            for (
                $a = md5($rand, true), $s = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', $unique = '',
                $f = 0;
                $f < 6;
                $g = ord($a[$f]), $unique .= $s[($g ^ ord($a[$f + 8])) - $g & 0x1F], $f++
            );

            break;
        case 8:
            // 8位
            $qidarr = @explode(".", uniqid('', true));
            $unique = $yCode[intval(date('Y')) - 2019] . $qidarr[1];
            break;
        case 11:
            // 生成一个唯一编号  生成唯一标示
            $unique = $yCode[intval(date('Y')) - 2019] . date('d') . substr(time(), -4) . substr(microtime(), 2, 4);
            break;
        case 16:
            if ($prefix) {
                // 有前缀
                // 加前缀16位 前缀两位字母
                $unique = $prefix . $yCode[intval(date('Y')) - 2019] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -8) . substr(microtime(), 2, 2);
            } else {
                // 16位
                $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K');
                $unique = $yCode[intval(date('Y')) - 2019] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -8) . substr(microtime(), 2, 4);
            }
            break;
        default:  
            break;
    }
    // 16位
    // date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8)
    
    return $unique;
}

// 内容转换
function changebody($bodys)
{    
    // 当前协议
    $http_type =  config('dzc.http_type');
    // 图片库
    $document_path =  config('dzc.document_path');

    // 转换body反斜线 替换图片路径
    return str_replace('src="' . $document_path, 'src="'. $http_type.'' . $_SERVER['HTTP_HOST'] . $document_path, $bodys);
}

// 获取多少天后的日期
function get_ab_date($today, $num)
{
    $todaystamp = strtotime($today);
    if($num >= 0){
        // return date($today, strtotime("+".$num." day"));
        return date('Y-m-d', $todaystamp + $num * 24 * 60 * 60);
    }else{
        return date('Y-m-d', $todaystamp - $num * 24 * 60 * 60);
    }
}

//获取两个时间戳相差时间
function get_time_diff($begin_time,$end_time)
{
    if($begin_time < $end_time){
        $starttime = $begin_time;
        $endtime = $end_time;
    }else{
        $starttime = $end_time;
        $endtime = $begin_time;
    }
    //计算天数
    $timediff = $endtime-$starttime;
    $days = intval($timediff/86400);
    //计算小时数
    $remain = $timediff%86400;
    $hours = intval($remain/3600);
    //计算分钟数
    $remain = $remain%3600;
    $mins = intval($remain/60);
    //计算秒数
    $secs = $remain%60;
    $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
    return $res;
}


