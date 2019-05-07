<?php
namespace elexiangmart\admin\controller;
use elexiangmart\admin\model\Cards as M;
use elexiangmart\admin\model\CardsLog as MLog;
use think\Db;
/**
 * ============================================================================

 * 卡密控制器
 */
class Cards extends Base{
	
    public function index(){
    	return $this->fetch("list");
    }
    /**
     * 获取分页
     */
    public function pageQuery(){
        $m = new M();
        return $m->pageQuery();
    }

    /**
     * 新增
     */
    public function add(){
        if($this->request->isAjax()){
            $rule = input('post.rule');
            $num = input('post.num');
            Db::startTrans();
            try{
                $card = new \card\Card();
                $checkrule = $card->checkrule($rule, 1);
                if ($checkrule === -2) {
                    return WSTReturn('生成规则有误', -1);
                }
                if ($num < 1) {
                    return WSTReturn('生成数量不能小于1', -1);
                }
                $card->make($rule, $num);
                $cardlist = $card->cardlist;
                $arr = array();
                foreach($cardlist as $one){
                    array_push($arr,['id'=>$one['cardNo'],'cardPwd'=>$one['cardPwd']]);
                }
                $res = db('cards')->insertAll($arr);
                if(!$res){
                    Db::rollback();
                    return WSTReturn('生成失败', -1);
                }

                $card_rule = serialize(array('rule' => $rule, 'num' => $num));
                $cardlog = array(
                    'cardrule' => $card_rule,
                    'dateline' => time(),
                    'description' => '',
                    'operation' => 1,//添加卡
                );
                $res = db('cards_log')->insert($cardlog);
                if(!$res){
                    Db::rollback();
                    return WSTReturn('生成失败', -1);
                }
            }catch (\Exception $e){
                Db::rollback();
                return WSTReturn('生成失败'.$e->getMessage(), -1);
            }
            Db::commit();
            return WSTReturn('生成成功', 1);
        }
        return $this->fetch();
    }

    /**
     * 删除
     */
    public function del(){
        $m = new M();
        return $m->del();
    }

    public function addLog()
    {
        
    }

    public function delLog()
    {

    }
    
}
