<?php
namespace elexiangmart\admin\model;
/**
 * ============================================================================

 * 商城配置业务处理
 */
use think\Db;
class Styles extends Base{
	/**
	 * 获取分类
	 */
	public function getCats(){
		return $this->distinct(true)->field('styleSys')->select();
	}
	/**
	 * 获取风格列表
	 */
	public function listQuery(){
		$styleSys = input('styleSys');
		$rs = $this->where('styleSys',$styleSys)->select();
		return ['sys'=>$styleSys,'list'=>$rs];
	}
	
    /**
	 * 编辑
	 */
	public function changeStyle(){
		 $id = (int)input('post.id');
		 $object = $this->get($id);
		 Db::startTrans();
         try{
		     $rs = $this->where('styleSys',$object['styleSys'])->update(['isUse'=>0]);
		     if(false !== $rs){
		         $object->isUse = 1;
		         $object->save();
		         cache('WST_CONF',null);
		         Db::commit();
		         return WSTReturn('操作成功',1);
		     }
		 }catch (\Exception $e) {
            Db::rollback();
            print_r($e);
            return WSTReturn('操作失败');
        }
         
    }
	
}
