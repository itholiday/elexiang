<?php
namespace elexiangmart\admin\model;
use think\Db;
/**
 * ============================================================================

 * 提现分类业务处理
 */
class CashDraws extends Base{
	/**
	 * 分页
	 */
	public function pageQuery(){
		$cashNo = input('cashNo');
		$cashSatus = input('cashSatus',-1);
        $where = [];
        if(in_array($cashSatus,[0,1]))$where['cashSatus'] = $cashSatus;
        if($cashNo!='')$where['cashNo'] = ['like','%'.$cashNo.'%'];
        return $this->where($where)->paginate(input('pagesize/d'))->toArray();
	}

	/**
	 * 获取提现详情
	 */
	public function getById(){
		$id = (int)input('id');
		return $this->get($id);
	}

	/**
	 * 处理提现
	 */
	public function handle(){
		$id = (int)input('cashId');
		$cash = $this->get($id);
		if(empty($cash))return WSTReturn('无效的提现申请记录');
		$user = model('users')->get($cash->targetId);
		if($user->lockMoney<$cash->money)return WSTReturn('操作失败，被冻结的金额小于提现金额');
		Db::startTrans();
		try{
            $cash->cashSatus = 1;
            $cash->cashRemarks = input('cashRemarks');
            $result = $cash->save();
            if(false != $result){

            	$user->lockMoney = $user->lockMoney-$cash->money;
            	$user->save();
            	//创建一条流水记录
            	$lm = [];
				$lm['targetType'] = 0;
				$lm['targetId'] = $cash->targetId;
				$lm['dataId'] = $id;
				$lm['dataSrc'] = 3;
				$lm['remark'] = '提现申请单【'.$cash->cashNo.'】申请提现¥'.$cash->money.'。'.(($cash->cashRemarks!='')?"【操作备注】：".$cash->cashRemarks:'');
				$lm['moneyType'] = 0;
				$lm['money'] = $cash->money;
				$lm['payType'] = 0;
				$lm['createTime'] = date('Y-m-d H:i:s');
				model('LogMoneys')->save($lm);
				//发送信息信息
				WSTSendMsg($cash->targetId,"您的提现申请单【".$cash->cashNo."】已通过，请留意您的账户信息",['from'=>5,'dataId'=>$id]);
				Db::commit();
				return WSTReturn('操作成功!',1);
            }
		}catch (\Exception $e) {
            Db::rollback();
        }
		return WSTReturn('操作失败!',-1);
	}
}
