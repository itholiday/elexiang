<?php
namespace elexiangmart\home\controller;
use elexiangmart\common\model\Ads as M;
/**
 * ============================================================================

 * 广告控制器
 */
class Ads extends Base{
	/**
	* 记录点击次数
	*/
    public function recordClick(){
        $m = new M();
        return $m->recordClick();
    }
}
