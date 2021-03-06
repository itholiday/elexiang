<?php
namespace elexiangmart\common\model;
use think\Db;
/**
 * ============================================================================

 * 资源分类类
 */
class GoodsCats extends Base{
	/**
	 * 获取列表
	 */
	public function listQuery($parentId,$isFloor = -1){
		$dbo = $this->where(['dataFlag'=>1,'isShow'=>1,'parentId'=>$parentId]);
		if($isFloor!=-1)$dbo->where('isFloor',$isFloor);
		return $dbo->order('catSort asc')->select();
	}
	
	/**
	 * 根据子分类获取其父级分类
	 */
	public function getParentIs($id,$data = array()){
		$data[] = $id;
		$parentId = $this->where('catId',$id)->value('parentId');
		if($parentId==0){
			krsort($data);
			return $data;
		}else{
			return $this->getParentIs($parentId, $data);
		}
	}
	
   /**
     * 获取首页楼层
     */
    public function getFloors(){
	    $cats1 = Db::name('goods_cats')->where(['dataFlag'=>1, 'isShow' => 1,'parentId'=>0,'isFloor'=>1])
		             ->field("catName,catId")->order('catSort asc')->limit(10)->select();
		if(!empty($cats1)){
			$ids = [];
			foreach ($cats1 as $key =>$v){
				$ids[] = $v['catId'];
			}
			$cats2 = [];
			$rs = Db::name('goods_cats')->where(['dataFlag'=>1, 'isShow' => 1,'parentId'=>['in',$ids],'isFloor'=>1])
				             ->field("parentId,catName,catId")->order('catSort asc')->select();
			foreach ($rs as $key => $v){
				$cats2[$v['parentId']][] = $v;
			}
			foreach ($cats1 as $key =>$v){
				$cats1[$key]['children'] = (isset($cats2[$v['catId']]))?$cats2[$v['catId']]:[];
			}
		}
		return $cats1;
    }
}
