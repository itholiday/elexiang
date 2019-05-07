<?php
namespace elexiangmart\admin\model;
use think\Db;
/**
 * ============================================================================

 * 资源类
 */
class Goods extends Base{
     /**
      *  上架资源列表
      */
	public function saleByPage(){
		$where = [];
		$where['g.goodsStatus'] = 1;
		$where['g.dataFlag'] = 1;
		$where['g.isSale'] = 1;
		$areaIdPath = input('areaIdPath');
		$goodsCatIdPath = input('goodsCatIdPath');
		$goodsName = input('goodsName');
		$shopName = input('shopName');
		if($areaIdPath !='')$where['areaIdPath'] = ['like',$areaIdPath."%"];
		if($goodsCatIdPath !='')$where['goodsCatIdPath'] = ['like',$goodsCatIdPath."%"];
		if($goodsName != '')$where['goodsName|goodsSn'] = ['like',"%$goodsName%"];
		if($shopName != '')$where['shopName|shopSn'] = ['like',"%$shopName%"];
		$keyCats = model('GoodsCats')->listKeyAll();
		$rs = $this->alias('g')->join('__SHOPS__ s','g.shopId=s.shopId','left')
		    ->where($where)
			->field('goodsId,goodsName,goodsSn,saleNum,shopPrice,g.shopId,goodsImg,s.shopName,goodsCatIdPath')
			->order('saleTime', 'desc')
			->paginate(input('pagesize/d'))->toArray();
		foreach ($rs['Rows'] as $key => $v){
			$rs['Rows'][$key]['verfiycode'] = WSTShopEncrypt($v['shopId']);
			$rs['Rows'][$key]['goodsCatName'] = self::getGoodsCatNames($v['goodsCatIdPath'],$keyCats);
		}
		return $rs;
	}
    public function getGoodsCatNames($goodsCatPath, $keyCats){
		$catIds = explode("_",$goodsCatPath);
		$catNames = array();
		for($i=0,$k=count($catIds);$i<$k;$i++){
			if($catIds[$i]=='')continue;
			if(isset($keyCats[$catIds[$i]]))$catNames[] = $keyCats[$catIds[$i]];
		}
		return implode("→",$catNames);
	}
	/**
	 * 审核中的资源
	 */
    public function auditByPage(){
    	$where['goodsStatus'] = 0;
		$where['g.dataFlag'] = 1;
		$where['isSale'] = 1;
		$areaIdPath = input('areaIdPath');
		$goodsCatIdPath = input('goodsCatIdPath');
		$goodsName = input('goodsName');
		$shopName = input('shopName');
		if($areaIdPath !='')$where['areaIdPath'] = ['like',$areaIdPath."%"];
		if($goodsCatIdPath !='')$where['goodsCatIdPath'] = ['like',$goodsCatIdPath."%"];
		if($goodsName != '')$where['goodsName|goodsSn'] = ['like',"%$goodsName%"];
		if($shopName != '')$where['shopName|shopSn'] = ['like',"%$shopName%"];
		$keyCats = model('GoodsCats')->listKeyAll();
		$rs = $this->alias('g')->join('__SHOPS__ s','g.shopId=s.shopId','left')
		    ->where($where)
			->field('goodsId,goodsName,goodsSn,saleNum,shopPrice,goodsImg,s.shopName,s.shopId,goodsCatIdPath')
			->order('saleTime', 'desc')
			->paginate(input('pagesize/d'))->toArray();
        foreach ($rs['Rows'] as $key => $v){
			$rs['Rows'][$key]['verfiycode'] =  WSTShopEncrypt($v['shopId']);
			$rs['Rows'][$key]['goodsCatName'] = self::getGoodsCatNames($v['goodsCatIdPath'],$keyCats);
		}
		return $rs;
	}
	/**
	 * 违规的资源
	 */
	public function illegalByPage(){
		$where['goodsStatus'] = -1;
		$where['g.dataFlag'] = 1;
		$where['isSale'] = 1;
		$areaIdPath = input('areaIdPath');
		$goodsCatIdPath = input('goodsCatIdPath');
		$goodsName = input('goodsName');
		$shopName = input('shopName');
		if($areaIdPath !='')$where['areaIdPath'] = ['like',$areaIdPath."%"];
		if($goodsCatIdPath !='')$where['goodsCatIdPath'] = ['like',$goodsCatIdPath."%"];
		if($goodsName != '')$where['goodsName|goodsSn'] = ['like',"%$goodsName%"];
		if($shopName != '')$where['shopName|shopSn'] = ['like',"%$shopName%"];
		$keyCats = model('GoodsCats')->listKeyAll();
		$rs = $this->alias('g')->join('__SHOPS__ s','g.shopId=s.shopId','left')
		    ->where($where)
			->field('goodsId,goodsName,goodsSn,goodsImg,s.shopName,s.shopId,illegalRemarks,goodsCatIdPath')
			->order('saleTime', 'desc')
			->paginate(input('pagesize/d'))->toArray();
		foreach ($rs['Rows'] as $key => $v){
			$rs['Rows'][$key]['verfiycode'] = WSTShopEncrypt($v['shopId']);
			$rs['Rows'][$key]['goodsCatName'] = self::getGoodsCatNames($v['goodsCatIdPath'],$keyCats);
		}
		return $rs;
	}
	
	/**
	 * 获取资源资料方便编辑
	 */
	public function getById($goodsId){
		$rs = $this->where(['shopId'=>(int)session('WST_USER.shopId'),'goodsId'=>$goodsId])->find();
		if(!empty($rs)){
			if($rs['gallery']!='')$rs['gallery'] = explode(',',$rs['gallery']);
			//获取规格值
			$specs = Db::name('spec_cats')->alias('gc')->join('__SPEC_ITEMS__ sit','gc.catId=sit.catId','inner')
			                      ->where(['sit.goodsId'=>$goodsId,'gc.isShow'=>1,'sit.dataFlag'=>1])
			                      ->field('gc.isAllowImg,sit.catId,sit.itemId,sit.itemName,sit.itemImg')
			                      ->order('gc.isAllowImg desc,gc.catSort asc,gc.catId asc')->select();
			$spec0 = [];
			$spec1 = [];                      
			foreach ($specs as $key =>$v){
				if($v['isAllowImg']==1){
					$spec0[] = $v;
				}else{
					$spec1[] = $v;
				}
			}
			$rs['spec0'] = $spec0;
			$rs['spec1'] = $spec1;
			//获取销售规格
			$rs['saleSpec'] = model('goodsSpecs')->where('goodsId',$goodsId)->field('id,isDefault,productNo,specIds,marketPrice,specPrice,specStock,warnStock,saleNum')->select();
			//获取属性值
			$rs['attrs'] = model('goodsAttributes')->where('goodsId',$goodsId)->field('attrId,attrVal')->select();
		}
		return $rs;
	}
	/**
	 * 获取资源资料在前台展示
	 */
     public function getBySale($goodsId){
     	$key = input('key');
		$rs = $this->where(['goodsId'=>$goodsId,'dataFlag'=>1])->find()->toArray();
		if(!empty($rs)){
			$rs['read'] = false;
			//判断是否可以公开查看
			$viKey = WSTShopEncrypt($rs['shopId']);
			if(($rs['isSale']==0 || $rs['goodsStatus']==0) && $viKey != $key)return [];
			if($key!='')$rs['read'] = true;
			//获取店铺信息
			$rs['shop'] = model('shops')->getBriefShop((int)$rs['shopId']);
			if(empty($rs['shop']))return [];
			$gallery = [];
			$gallery[] = $rs['goodsImg'];
			if($rs['gallery']!=''){
				$tmp = explode(',',$rs['gallery']);
				$gallery = array_merge($gallery,$tmp);
			}
			$rs['gallery'] = $gallery;
			//获取规格值
			$specs = Db::name('spec_cats')->alias('gc')->join('__SPEC_ITEMS__ sit','gc.catId=sit.catId','inner')
			                      ->where(['sit.goodsId'=>$goodsId,'gc.isShow'=>1,'sit.dataFlag'=>1])
			                      ->field('gc.isAllowImg,gc.catName,sit.catId,sit.itemId,sit.itemName,sit.itemImg')
			                      ->order('gc.isAllowImg desc,gc.catSort asc,gc.catId asc')->select();                     
			foreach ($specs as $key =>$v){
				$rs['spec'][$v['catId']]['name'] = $v['catName'];
				$rs['spec'][$v['catId']]['list'][] = $v;
			}
			//获取销售规格
			$sales = model('goodsSpecs')->where('goodsId',$goodsId)->field('id,isDefault,productNo,specIds,marketPrice,specPrice,specStock')->select();
			if(!empty($sales)){
				foreach ($sales as $key =>$v){
					$str = explode(':',$v['specIds']);
					sort($str);
					unset($v['specIds']);
					$rs['saleSpec'][implode(':',$str)] = $v;
				}
			}
			//获取资源属性
			$rs['attrs'] = Db::name('attributes')->alias('a')->join('__GOODS_ATTRIBUTES__ ga','a.attrId=ga.attrId','inner')
			                   ->where(['a.isShow'=>1,'dataFlag'=>1,'goodsId'=>$goodsId])->field('a.attrName,ga.attrVal')
			                   ->order('attrSort asc')->select();
		}
		return $rs;
	}
	
	/**
	 * 删除资源
	 */
	public function del(){
	    $id = input('post.id/d');
		$data = [];
		$data['dataFlag'] = -1;
		Db::startTrans();
		try{
		    $result = $this->update($data,['goodsId'=>$id]);
	        if(false !== $result){
	        	Db::name('carts')->where('goodsId',$id)->delete();
	        	WSTUnuseImage('goods','goodsImg',$id);
		        WSTUnuseImage('goods','gallery',$id);
		        Db::commit();
		        //标记删除购买车
	        	return WSTReturn("删除成功", 1);
	        }
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('删除失败',-1);
	}
	/**
	  * 批量删除资源
	  */
	 public function batchDel(){
	 	$shopId = (int)session('WST_USER.shopId');
	   	$ids = input('post.ids/a');
	   	Db::startTrans();
		try{
		   	$rs = $this->where(['goodsId'=>['in',$ids],
		   						'shopId'=>$shopId])->setField('dataFlag',-1);
			if(false !== $rs){
				Db::name('carts')->where(['goodsId'=>['in',$ids]])->delete();
				//标记删除购买车
			    foreach ($ids as $v){
					WSTUnuseImage('goods','goodsImg',(int)$v);
			        WSTUnuseImage('goods','gallery',(int)$v);
				}
				Db::commit();
	        	return WSTReturn("删除成功", 1);
	        }
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('删除失败',-1);
	 }

	/**
	* 设置资源违规状态
	*/
	public function illegal(){
		$illegalRemarks = input('post.illegalRemarks');		
		$id = (int)input('post.id');
		if($illegalRemarks=='')return WSTReturn("请输入违规原因");
		//判断资源状态
		$rs = $this->alias('g')->join('__SHOPS__ s','g.shopId=s.shopId','left')->where('goodsId',$id)
		           ->field('s.userId,g.goodsName,g.goodsSn,g.goodsStatus,g.goodsId')->find();
		if((int)$rs['goodsId']==0)return WSTReturn("无效的资源");
		if((int)$rs['goodsStatus']!=1)return WSTReturn("操作失败，资源状态已发生改变，请刷新后再尝试");
		Db::startTrans();
		try{
			$res = $this->setField(['goodsId'=>$id,'goodsStatus'=>-1,'illegalRemarks'=>$illegalRemarks]);
			if($res!==false){
				Db::name('carts')->where(['goodsId'=>$id])->delete();
				//发送一条商家信息
				WSTSendMsg($rs['userId'],"您的资源".$rs['goodsName']."【".$rs['goodsSn']."】因【".$illegalRemarks."】被下架处理。",['from'=>2,'dataId'=>$id]);
				Db::commit();
				return WSTReturn('操作成功',1);
			}
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('删除失败',-1);
	}
   /**
	* 通过资源审核
	*/
	public function allow(){	
		$id = (int)input('post.id');
		//判断资源状态
		$rs = $this->alias('g')->join('__SHOPS__ s','g.shopId=s.shopId','left')->where('goodsId',$id)
		           ->field('s.userId,g.goodsName,g.goodsSn,g.goodsStatus,g.goodsId')->find();
		if((int)$rs['goodsId']==0)return WSTReturn("无效的资源");
		if((int)$rs['goodsStatus']==1)return WSTReturn("操作失败，资源状态已发生改变，请刷新后再尝试");
		Db::startTrans();
		try{
			$res = $this->setField(['goodsId'=>$id,'goodsStatus'=>1]);
			if($res!==false){
				//发送一条商家信息
				WSTSendMsg($rs['userId'],"您的资源".$rs['goodsName']."【".$rs['goodsSn']."】已审核通过。",['from'=>2,'dataId'=>$id]);
				Db::commit();
				return WSTReturn('操作成功',1);
			}
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('删除失败',-1);
	}

	/**
	 * 查询资源
	 */
	public function searchQuery(){
		$goodsCatatId = (int)input('post.goodsCatId');
		if($goodsCatatId<=0)return [];
		$goodsCatIds = WSTGoodsCatPath($goodsCatatId);
		$key = input('post.key');
		$where = [];
		$where['g.dataFlag'] = 1;
		$where['g.isSale'] = 1;
		$where['g.goodsStatus'] = 1;
		$where['goodsCatIdPath'] = ['like',implode('_',$goodsCatIds).'_%'];
		if($key!='')$where['goodsName|shopName'] = ['like','%'.$key.'%'];
		return $this->alias('g')->join('__SHOPS__ s','g.shopId=s.shopId','inner')
		     ->where($where)->field('g.goodsName,s.shopName,g.goodsId')->limit(50)->select();
	}
}
