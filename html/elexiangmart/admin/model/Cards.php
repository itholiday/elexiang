<?php

namespace elexiangmart\admin\model;

use think\Db;

/**
 * ============================================================================
 */
class Cards extends Base
{
    /**
     * 分页
     */
    public function pageQuery()
    {
        $where = [];
        $where['a.dataFlag'] = 1;
        $pt = (int)input('positionType');
        $apId = (int)input('adPositionId');
        if ($pt > 0) $where['a.positionType'] = $pt;
        if ($apId != 0) $where['a.adPositionId'] = $apId;


        return Db::name('ads')->alias('a')
            ->join('ad_positions ap', 'a.positionType=ap.positionType AND a.adPositionId=ap.positionId AND ap.dataFlag=1', 'left')
            ->field('adId,adName,adPositionId,adURL,adStartDate,adEndDate,adPositionId,adFile,adClickNum,ap.positionName,a.adSort')
            ->where($where)->order('adId desc')
            ->order('adSort', 'asc')
            ->paginate(input('pagesize/d'));
    }


    /**
     * 删除
     */
    public function del()
    {
        $id = (int)input('post.id/d');
        Db::startTrans();
        try {
            $result = $this->setField(['adId' => $id, 'dataFlag' => -1]);
            WSTUnuseImage('ads', 'adFile', $id);
            if (false !== $result) {
                Db::commit();
                return WSTReturn("删除成功", 1);
            }
        } catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('删除失败', -1);
        }
    }
}
