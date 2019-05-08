<?php

namespace elexiangmart\home\controller;

use think\Db;
use elexiangmart\home\model\Payments as M;

/**
 * ============================================================================
 * 余额支付控制器
 */
class Usermoney extends Base
{

    /**
     * 生成支付代码
     */
    function getusermoneyURL()
    {
        $m = new M();
        $obj["orderId"] = input("id/s");
        $obj["isBatch"] = (int)input("isBatch/d");
        $isBatch = $obj['isBatch'];
        $orderId = $obj['orderId'];

        $data = model('Orders')->checkOrderPay($obj);
        if ($data["status"] == 1) {
            $userId = (int)session('WST_USER.userId');
            $obj["userId"] = $userId;
            $obj["orderId"] = input("id");
            $obj["isBatch"] = (int)input("isBatch");
            $orderAmount = $m->getPayOrders($obj);
            $userinfo = db('users')->where('userId', $userId)->find();
            if ($userinfo['userMoney'] < $orderAmount) {
                $this->error('余额不足，请充值','','home/addmoney/index');
            }
            Db::startTrans();
            try{
                //扣钱
                $res = db('users')->where('userId', $userId)->setDec('userMoney', $orderAmount);
                if (!$res) {
                    Db::rollback();
                    $this->error('支付失败');
                }
                //写日志
                $data = array();
                $data["needPay"] = 0;
                $data["isPay"] = 1;
                $data["orderStatus"] = 0;
                $rs = model('orders')->where(['orderId'=>$obj['orderId']])->update($data);

                if ($orderAmount>0 && false != $rs) {
                    $where = ["o.userId" => $userId];
                    if ($isBatch == 1) {
                        $where["orderunique"] = $orderId;
                    } else {
                        $where["orderId"] = $orderId;
                    }
                    $list = Db::name('orders')->alias('o')->join('__SHOPS__ s', 'o.shopId=s.shopId ', 'inner')
                        ->where($where)->field('orderId,orderNo,s.userId')
                        ->select();
                    if (!empty($list)) {
                        foreach ($list as $key => $v) {
                            $orderId = $v["orderId"];
                            //新增订单日志
                            $logOrder = [];
                            $logOrder['orderId'] = $orderId;
                            $logOrder['orderStatus'] = 0;
                            $logOrder['logContent'] = "订单已支付,下单成功";
                            $logOrder['logUserId'] = $userId;
                            $logOrder['logType'] = 0;
                            $logOrder['logTime'] = date('Y-m-d H:i:s');
                            Db::name('log_orders')->insert($logOrder);
                            //创建一条充值流水记录
                            $lm = [];
                            $lm['targetType'] = 0;
                            $lm['targetId'] = $userId;
                            $lm['dataId'] = $orderId;
                            $lm['dataSrc'] = 1;
                            $lm['remark'] = '交易订单【' . $v['orderNo'] . '】充值¥' . $orderAmount;
                            $lm['moneyType'] = 1;
                            $lm['money'] = $orderAmount;
                            $lm['createTime'] = date('Y-m-d H:i:s');
                            model('LogMoneys')->save($lm);
                            //创建一条支出流水记录
                            $lm = [];
                            $lm['targetType'] = 0;
                            $lm['targetId'] = $userId;
                            $lm['dataId'] = $orderId;
                            $lm['dataSrc'] = 1;
                            $lm['remark'] = '交易订单【' . $v['orderNo'] . '】支出¥' . $orderAmount;
                            $lm['moneyType'] = 0;
                            $lm['money'] = $orderAmount;
                            $lm['payType'] = 0;
                            $lm['createTime'] = date('Y-m-d H:i:s');
                            model('LogMoneys')->save($lm);

                            //发送一条商家信息
                            $msgContent = "订单【" . $v['orderNo'] . "】用户已支付完成，请尽快发货哦~";
                            WSTSendMsg($v["userId"], $msgContent, ['from' => 1, 'dataId' => $orderId]);
                        }
                    }
                }
            }catch(\Exception $e){
                Db::rollback();
                $this->error('支付失败'.$e->getMessage());
            }
            Db::commit();
            $this->success('',"home/orders/waitReceive");
        } else {
            $this->error($data['msg']);
        }
    }
}
