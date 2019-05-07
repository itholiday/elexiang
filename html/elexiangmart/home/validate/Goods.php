<?php 
namespace elexiangmart\home\validate;
use think\Validate;
/**
 * ============================================================================

 * 资源验证器
 */
class Goods extends Validate{
	protected $rule = [
        ['goodsName'  ,'require|max:300','请输入资源编号|资源名称不能超过100个字符'],
        ['goodsImg'  ,'require','请上传资源图片'],
        ['goodsSn'  ,'checkGoodsSn:1','请输入资源编号'],
        ['productNo'  ,'checkProductNo:1','请输入资源货号'],
        ['marketPrice'  ,'require|float','请输入市场价格|市场价格只能为数字'],
        ['shopPrice'  ,'require|float','请输入店铺价格|店铺价格只能为数字'],
        ['goodsUnit'  ,'require','请输入资源单位'],
        ['isSale'  ,'in:,0,1','无效的上架状态'],
        ['isRecom'  ,'in:,0,1','无效的推荐状态'],
        ['isBest'  ,'in:,0,1','无效的精品状态'],
        ['isNew'  ,'in:,0,1','无效的新品状态'],
        ['isHot'  ,'in:,0,1','无效的热销状态'],
        ['goodsCatId'  ,'require','请选择完整资源分类'],
        ['goodsDesc','require','请输入资源描述'],
        ['specsIds','checkSpecsIds:1','请填写完整资源规格信息']
    ];
    /**
     * 检测资源编号
     */
    protected function checkGoodsSn($value){
    	$goodsId = Input('post.goodsId/d',0);
    	$key = Input('post.goodsSn');
    	if($key=='')return '请输入资源编号';
    	$isChk = model('Goods')->checkExistGoodsKey('goodsSn',$key,$goodsId);
    	if($isChk)return '对不起，该资源编号已存在';
    	return true;
    }
    /**
     * 检测资源货号
     */
    protected function checkProductNo($value){
    	$goodsId = Input('post.goodsId/d',0);
    	$key = Input('post.productNo');
    	if($key=='')return '请输入资源货号';
    	$isChk = model('Goods')->checkExistGoodsKey('productNo',$key,$goodsId);
    	if($isChk)return '对不起，该资源货号已存在';
    	return true;
    }
    /**
     * 检测资源规格是否填写完整
     */
    public function checkSpecsIds(){
    	$specsIds = input('post.specsIds');
    	if($specsIds!=''){
	    	$str = explode(',',$specsIds);
	    	$specsIds = [];
	    	foreach ($str as $v){
	    		$vs = explode('-',$v);
	    		foreach ($vs as $vv){
	    		   if(!in_array($vv,$specsIds))$specsIds[] = $vv;
	    		}
	    	}
    		//检测规格名称是否填写完整
    		foreach ($specsIds as $v){
    			if(input('post.specName_'.$v)=='')return '请填写完整资源规格值sn'.'specName_'.$v;
    		}
    		//检测销售规格是否完整	
    		foreach ($str as $v){
    			if(input('post.productNo_'.$v)=='')return '请填写完整资源销售规格信息1';
    			if(input('post.marketPrice_'.$v)=='')return '请填写完整资源销售规格信息2';
    			if(input('post.specPrice_'.$v)=='')return '请填写完整资源销售规格信息3';
    			if(input('post.specStock_'.$v)=='')return '请填写完整资源销售规格信息4';
    			if(input('post.warnStock_'.$v)=='')return '请填写完整资源销售规格信息5';
    		}
    		if(input('post.defaultSpec')=='')return '请选择推荐规格';
    	}
    	return true;
    }
}