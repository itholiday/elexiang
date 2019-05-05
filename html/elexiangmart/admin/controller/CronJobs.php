<?php
namespace elexiangmart\admin\controller;
use elexiangmart\admin\model\CronJobs as M;
/**
 * ============================================================================

 * 定时任务控制器
 */
class CronJobs extends Base{
	/**
	 * 取消未付款订单
	 */
	public function autoCancelNoPay(){
		$m = new M();
        return $m->autoCancelNoPay();
	}
	/**
	 * 自动好评
	 */
	public function autoAppraise(){
        $m = new M();
        return $m->autoAppraise();
	}
	/**
	 * 自动确认收货
	 */
	public function autoReceive(){
	 	$m = new M();
        return $m->autoReceive();
	}
}