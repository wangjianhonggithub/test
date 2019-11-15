<?php
namespace app\manage\controller;
use think\facade\Config;
use think\facade\App;
use ZipArchive;
use think\Db;
use app\common\model\Market as MarketModel;
use app\common\model\LoginLog;
use app\common\model\App as AppModel;
/**
 * 后台首页
 * Class Index
 * @package app\manage\controller
 */
class Index extends Controller
{
    /**
     * 后台首页
     * @return mixed
     */
    public function index()
    {
        $model = new MarketModel;
        // $data = $model->getHomeData();
		
		// 进行中课程
		$numbers['coursenum'] = db('course')->where(['ispast'=>1,'ishow'=>1,'isdel'=>1])->count();
		$numbers['coursenum_his'] = db('course')->where(['ispast'=>2,'ishow'=>1,'isdel'=>1])->count();
		$numbers['ordernum'] = db('order')->where(['isdel'=>1])->count();
		$numbers['usernum'] = db('user')->where(['isdel'=>1])->count();

		$this->assign('numbers',$numbers);

        
        $log=(new LoginLog)->getAll();
		
		$version=AppModel::getAppCache();

		$this->assign('statusClass', $this->statusClass);
		$this->assign('server', $this->server()); 		// 服务器信息
		$this->assign('phpinfo', $this->phpinfo());		// PHP环境要求
		$this->assign('writeable', $this->writeable());	// 目录权限监测

        return $this->fetch('index', compact('data','version','log'));
    }


	/**
	 * 状态class
	 * @var array
	 */
	private $statusClass = [
		'normal' => '',
		'warning' => 'am-active',
		'danger' => 'am-danger'
	];

	/**
	 * 服务器信息
	 * @return array
	 */
	private function server()
	{
		return [
			'system' => [
				'name' => '服务器操作系统',
				'value' => PHP_OS,
				'status' => PHP_SHLIB_SUFFIX === 'dll' ? 'warning' : 'normal',
				'remark' => '建议使用 Linux 系统以提升程序性能'
			],
			'webserver' => [
				'name' => 'Web服务器环境',
				'value' => $this->request->server('SERVER_SOFTWARE'),
				'status' => PHP_SAPI === 'isapi' ? 'warning' : 'normal',
				'remark' => '建议使用 Apache 或 Nginx 以提升程序性能'
			],
			'php' => [
				'name' => 'PHP版本',
				'value' => PHP_VERSION,
				'status' => version_compare(PHP_VERSION, '5.4.0') === -1 ? 'danger' : 'normal',
				'remark' => 'PHP版本必须为 5.4.0 以上'
			],
			'upload_max' => [
				'name' => '文件上传限制',
				'value' => @ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknow',
				'status' => 'normal',
				'remark' => ''
			],
			'web_path' => [
				'name' => '程序运行目录',
				'value' => WEB_PATH,
				'status' => 'normal',
				'remark' => ''
			],
		];
	}

	/**
	 * PHP环境要求
	 * @return array
	 */
	private function phpinfo()
	{
		return [
			// 'php_version' => [
			// 	'name' => 'PHP版本',
			// 	'value' => '5.4.0及以上',
			// 	'status' => version_compare(PHP_VERSION, '5.4.0') === -1 ? 'danger' : 'normal',
			// 	'remark' => 'PHP版本必须为 5.4.0及以上'
			// ],
			'curl' => [
				'name' => 'CURL',
				'value' => '支持',
				'status' => extension_loaded('curl') && function_exists('curl_init') ? 'normal' : 'danger',
				'remark' => '您的PHP环境不支持CURL, 系统无法正常运行'
			],
			'openssl' => [
				'name' => 'OpenSSL',
				'value' => '支持',
				'status' => extension_loaded('openssl') ? 'normal' : 'danger',
				'remark' => '没有启用OpenSSL, 将无法访问微信平台的接口'
			],
			'pdo' => [
				'name' => 'PDO',
				'value' => '支持',
				'status' => extension_loaded('PDO') && extension_loaded('pdo_mysql') ? 'normal' : 'danger',
				'remark' => '您的PHP环境不支持PDO, 系统无法正常运行'
			],
			'bcmath' => [
				'name' => 'BCMath',
				'value' => '支持',
				'status' => extension_loaded('bcmath') ? 'normal' : 'danger',
				'remark' => '您的PHP环境不支持BCMath, 系统无法正常运行'
			],
		];
	}

	/**
	 * 目录权限监测
	 */
	private function writeable()
	{
		$paths = [
			'uploads' => realpath(WEB_PATH) . '/uploads/',
			'wxpay_log' => realpath(APP_PATH) . '/common/library/wechat/logs/',
			'behavior_log' => realpath(APP_PATH) . '/task/behavior/logs/',
		];
		return [
			'uploads' => [
				'name' => '文件上传目录',
				'value' => $paths['uploads'],
				'status' => $this->checkWriteable($paths['uploads']) ? 'normal' : 'danger',
				'remark' => '目录不可写，系统将无法正常上传文件'
			],
			// 'wxpay_log' => [
			// 	'name' => '微信支付日志目录',
			// 	'value' => $paths['wxpay_log'],
			// 	'status' => $this->checkWriteable($paths['wxpay_log']) ? 'normal' : 'danger',
			// 	'remark' => '目录不可写，系统将无法正常上传文件'
			// ],
			// 'behavior_log' => [
			// 	'name' => '自动任务日志目录',
			// 	'value' => $paths['behavior_log'],
			// 	'status' => $this->checkWriteable($paths['behavior_log']) ? 'normal' : 'danger',
			// 	'remark' => '目录不可写，系统将无法正常上传文件'
			// ],
		];
	}

	/**
	 * 检查目录是否可写
	 * @param $path
	 * @return bool
	 */
	private function checkWriteable($path){
		try {
			!is_dir($path) && mkdir($path, 0755);
			if (!is_dir($path))
				return false;
			$fileName = $path . '/_test_write.txt';
			if ($fp = fopen($fileName, 'w')) {
				return fclose($fp) && unlink($fileName);
			}
		} catch (\Exception $e) { }
		return false;
	}


	/**
     * 意见箱
     * @return mixed
     */
    public function proposal()
    {
        
		return $this->fetch('proposal');
		
        
    }
	
	  public function Cross()
    {
		$data=curlPost('https://day.iirr5.com/home/up/index',$this->postData('proposal'));
		$data=json_decode($data,true);
		if($data['code']==200){
			 return $this->renderSuccess('提交成功');
		} 
    }
	
	 /**
     * 在线升级
     * @return mixed
     */
	public function update()	
	{	
		$version=AppModel::getAppCache();
        $hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        $updatehost = "http://day.iirr5.com/index.php/Home/Index/updata";
        $updatehosturl = $updatehost . "?a=update&v=" . $version['ver'] . "&u=" . $hosturl;
        $updatenowinfo = file_get_contents($updatehosturl);
		$file = json_decode($updatenowinfo,true);
		//dump($file);die;
		if($file['code']==='103') {
			 return $this->renderError($file['msg']);exit();
		}
	    $header = get_headers($file['file'], true);
        if (isset($header[0]) && (strpos($header[0], '200') || strpos($header[0], '304')) && strstr($file['file'], 'zip')){
			$rootPath = App::getRootPath();
			$updatedir = $rootPath.'runtime/log/';
            $updatezip = $this->getFile($file['file'],$file['version'],$updatedir);
			$zip = new ZipArchive;
			$res=$zip->open($updatezip['save_path']); 
			//解压缩到test文件夹 
			$archive = $zip->extractTo($rootPath);
			$zip->close(); 
            if ($archive !==true){
                $data['msg'] = "升级失败";
            }else{
                $sqlfile = $updatedir .$file['version'] .'/update.sql';
				if(file_exists($sqlfile)){
					$sql = file_get_contents($sqlfile);
					if($sql){
						$str = explode(PHP_EOL, $sql);
						foreach($str as $v){
							$v=str_replace('bfb_',Config::get('database.prefix'),$v);
							$result=Db::execute($v);
						}
					}
				}
				(new AppModel)->deleteCache();
                $data['msg'] = "升级完成！请检查是否还有新的更新包";               
            }
			unlink($updatezip['save_path']);
			$data['ver']=$file['version'];
		    (new AppModel)->edit($data);
			 return $this->renderSuccess($data['msg']);
        }else{
			 $data['msg'] = "升级包不存在";
			 return $this->renderError($data['msg']);
		}
	}

	 /**
     * 获取文件
     * @return mixed
     */
	function getFile($url, $filename,$save_dir,$type = 0) {
		$filename=$filename.'.zip';
		//获取远程文件所采用的方法
		ob_start();
		readfile($url);
		$content = ob_get_contents();
		ob_end_clean();
		$fp2 = @fopen($save_dir . $filename, 'a');
		fwrite($fp2, $content);
		fclose($fp2);
		unset($content, $url);
		return array(
			'file_name' => $filename,
			'save_path' => $save_dir . $filename
		);
	}

	public function add(){
        return $this->fetch('index1');
    }
}