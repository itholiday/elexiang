<?php
namespace elexiangmart\home\controller;
/**
 * ============================================================================

 * 基础控制器
 */
use think\Controller;
class Base extends Controller {
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}

	protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
    	$style = WSTConf('CONF.wstPCStyle')?WSTConf('CONF.wsthomeStyle'):'default';
    	$replace['__STYLE__'] = str_replace('/index.php','',\think\Request::instance()->root()).'/elexiangmart/home/view/'.WSTConf('CONF.wsthomeStyle');
        return $this->view->fetch($style."/".$template, $vars, $replace, $config);
    }

	/**
	 * 上传图片
	 */
	public function uploadPic(){
		return WSTUploadPic(0);
	}
	/**
    * 编辑器上传文件
    */
    public function editorUpload(){
           return WSTEditUpload(0);
    }
	
	/**
	 * 获取验证码
	 */
	public function getVerify(){
		WSTVerify();
	}

}