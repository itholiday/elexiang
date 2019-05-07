<?php
namespace elexiangmart\home\model;
/**
 * ============================================================================

 * 资源属性分类
 */
class Attributes extends Base{
	/**
	 * 获取可供筛选的资源属性
	 */
	public function listQueryByFilter($catId){
		$ids = model('GoodsCats')->getParentIs($catId);
		if(!empty($ids)){
			$catIds = [];
			foreach ($ids as $key =>$v){
				$catIds[] = $v;
			}
			$attrs = $this->where(['goodsCatId'=>['in',$catIds],'isShow'=>1,'dataFlag'=>1,'attrType'=>['<>',0]])
			     ->field('attrId,attrName,attrVal')->order('attrSort asc')->select();
			foreach ($attrs as $key =>$v){
			    $attrs[$key]['attrVal'] = explode(',',$v['attrVal']);
			}
			return $attrs;
		}
		return [];
	}
}
