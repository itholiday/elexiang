<?php 
namespace elexiangmart\home\model;
use think\Db;
use think\Loader;
/**
 * ============================================================================

 * 导入类
 */
class Imports{
	/**
	 * 上传资源数据
	 */
	public function importGoods($data){
		Loader::import('phpexcel.PHPExcel.IOFactory');
		$objReader = \PHPExcel_IOFactory::load(WSTRootPath().json_decode($data)->route.json_decode($data)->name);
		$objReader->setActiveSheetIndex(0);
		$sheet = $objReader->getActiveSheet();
		$rows = $sheet->getHighestRow();
		$cells = $sheet->getHighestColumn();
		//数据集合
        $readData = [];
        $shopId = (int)session('WST_USER.shopId');
        $importNum = 0;
        $goodsCatMap = []; //记录最后一级资源分类
        $goodsCatPathMap = [];//记录资源分类路径
        $shopCatMap = [];//记录店铺分类
        $goodsCat1Map = [];//记录最后一级资源分类对应的一级分类
        $tmpGoodsCatId = 0;
        $goodsCatBrandMap = [];//资源分类和品牌的对应关系
        //生成订单
		Db::startTrans();
		try{
	        //循环读取每个单元格的数据
	        for ($row = 3; $row <= $rows; $row++){//行数是以第3行开始
	        	$tmpGoodsCatId = 0;
	        	$goods = [];
	            $goods['shopId'] = $shopId;
	            $goods['goodsName'] = trim($sheet->getCell("A".$row)->getValue());
	            if($goods['goodsName']=='')break;//如果某一行第一列为空则停止导入
	            $goods['goodsSn'] = trim($sheet->getCell("B".$row)->getValue());
	            $goods['productNo'] = trim($sheet->getCell("C".$row)->getValue());
	            $goods['marketPrice'] = trim($sheet->getCell("D".$row)->getValue());
	            $goods['shopPrice'] = trim($sheet->getCell("E".$row)->getValue());
	            $goods['goodsStock'] = trim($sheet->getCell("F".$row)->getValue());
	            $goods['warnStock'] = trim($sheet->getCell("G".$row)->getValue());
	            $goods['goodsUnit'] = trim($sheet->getCell("H".$row)->getValue());
	            $goods['goodsSeoKeywords'] = trim($sheet->getCell("I".$row)->getValue());
	            $goods['goodsTips'] = trim($sheet->getCell("J".$row)->getValue());
	            $goods['isRecom'] = (trim($sheet->getCell("K".$row)->getValue())!='')?1:0;
	            $goods['isBest'] = (trim($sheet->getCell("L".$row)->getValue())!='')?1:0;
	            $goods['isNew'] = (trim($sheet->getCell("M".$row)->getValue())!='')?1:0;
	            $goods['isHot'] = (trim($sheet->getCell("N".$row)->getValue())!='')?1:0;
	            //查询商城分类
	            $goodsCat = trim($sheet->getCell("O".$row)->getValue());
	            if(!empty($goodsCat)){
	            	//先判断集合是否存在，不存在的时候才查数据库
	            	if(isset($goodsCatMap[$goodsCat])){
	            		$goods['goodsCatId'] = $goodsCatMap[$goodsCat];
		            	$goods['goodsCatIdPath'] = $goodsCatPathMap[$goodsCat];
		            	$tmpGoodsCatId = $goodsCat1Map[$goodsCat];
	            	}else{
		            	$goodsCatId = Db::name('goods_cats')->where(['catName'=>$goodsCat,'dataFlag'=>1])->field('catId')->find();
		            	if(!empty($goodsCatId['catId'])){
		            		$goodsCats = model('GoodsCats')->getParentIs($goodsCatId['catId']);
		            		$goods['goodsCatId'] = $goodsCatId['catId'];
		            		$goods['goodsCatIdPath'] = implode('_',$goodsCats)."_";
		            		//放入集合
		            		$goodsCatMap[$goodsCat] = $goodsCatId['catId'];
		            		$goodsCatPathMap[$goodsCat] = implode('_',$goodsCats)."_";
		            		$goodsCat1Map[$goodsCat] = $goodsCats[0];
		            		$tmpGoodsCatId = $goodsCats[0];
		            	}
	            	}
	            }
	            //查询店铺分类
	            $shopGoodsCat = trim($sheet->getCell("P".$row)->getValue());
	            if(!empty($shopGoodsCat)){
	            	//先判断集合是否存在，不存在的时候才查数据库
	            	if(isset($shopCatMap[$shopGoodsCat])){
	            		$goods['shopCatId1'] = $shopCatMap[$shopGoodsCat]['s1'];
		            	$goods['shopCatId2'] = $shopCatMap[$shopGoodsCat]['s2'];
	            	}else{
		            	$shopCat= Db::name("shop_cats")->alias('sc1')
		            	->join('__SHOP_CATS__ sc2','sc2.parentId=sc1.catId','left')
		            	->field('sc1.catId catId1,sc2.catId catId2,sc2.catName')
		            	->where(['sc1.shopId'=> $shopId,'sc1.dataFlag'=>1,'sc2.catName'=>$shopGoodsCat])
		            	->find();
		            	if(!empty($shopCat)){
		            		$goods['shopCatId1'] = $shopCat['catId1'];
		            		$goods['shopCatId2'] = $shopCat['catId2'];
		            		//放入集合
		            		$shopCatMap[$shopGoodsCat] = [];
		            		$shopCatMap[$shopGoodsCat]['s1'] = $goods['shopCatId1'];
		            		$shopCatMap[$shopGoodsCat]['s2'] = $goods['shopCatId2'];
		            	}
	            	}
	            }
	            //查询品牌
	            $brand = trim($sheet->getCell("Q".$row)->getValue());
	            if(!empty($brand)){
	            	if(isset($goodsCatBrandMap[$brand])){
		            	$goods['brandId'] = $goodsCatBrandMap[$brand];
	            	}else{
	            	    $brands = Db::name('brands')->alias('a')->join('__CAT_BRANDS__ cb','a.brandId=cb.brandId','inner')
		            	            ->where(['catId'=>$tmpGoodsCatId,'brandName'=>$brand,'dataFlag'=>1])->field('a.brandId')->find();
		            	if(!empty($brands)){
		            		$goods['brandId'] = $brands['brandId'];
		            		$goodsCatBrandMap[$brand] = $brands['brandId'];
		            	}
	            	}
	            }
	            $goods['goodsDesc'] = trim($sheet->getCell("R".$row)->getValue());
	            $goods['isSale'] = 0;
	            $goods['goodsStatus'] = (WSTConf("CONF.isGoodsVerify")==1)?0:1;
	            $goods['dataFlag'] = 1;
	            $goods['saleTime'] = date('Y-m-d H:i:s');
	            $goods['createTime'] = date('Y-m-d H:i:s');
	            $readData[] = $goods;
	            $importNum++;
	        }
            if(count($readData)>0){
            	$list = model('Goods')->saveAll($readData);
            	//建立资源评分记录
            	$goodsScores = [];
            	foreach ($list as $key =>$v){
					$gs = [];
					$gs['goodsId'] = $v['goodsId'];
					$gs['shopId'] = $shopId;
					$goodsScores[] = $gs;
            	}
            	if(count($goodsScores)>0)Db::name('goods_scores')->insertAll($goodsScores);
            }
            Db::commit();
            return json_encode(['status'=>1,'importNum'=>$importNum]);
		}catch (\Exception $e) {
			print_r($e);
            Db::rollback();
            return json_encode(WSTReturn('导入资源失败',-1));
        }
	}
}