<?php
namespace elexiangmart\home\controller;
use elexiangmart\common\model\Favorites as M;
/**
 * ============================================================================

 * 收藏控制器
 */
class Favorites extends Base{
	/**
	 * 关注的资源
	 */
	public function goods(){
		return $this->fetch('users/favorites/list_goods');
	}
	/**
	 * 关注的店铺
	 */
	public function shops(){
		return $this->fetch('users/favorites/list_shops');
	}
	/**
	 * 关注的资源列表
	 */
	public function listGoodsQuery(){
		$m = new M();
		$data = $m->listGoodsQuery();
		return WSTReturn("", 1,$data);
	}
	/**
	 * 关注的店铺列表
	 */
	public function listShopQuery(){
		$m = new M();
		$data = $m->listShopQuery();
		return WSTReturn("", 1,$data);
	}
	/**
	 * 取消关注
	 */
	public function cancel(){
		$m = new M();
		$rs = $m->del();
		return $rs;
	}
	/**
	 * 增加关注
	 */
	public function add(){
		$m = new M();
		$rs = $m->add();
		return $rs;
	}
}
