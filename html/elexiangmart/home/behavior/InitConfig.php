<?php
namespace elexiangmart\home\behavior;
/**
 * ============================================================================

 * 初始化基础数据
 */
class InitConfig 
{
    public function run(&$params){
        WSTConf('protectedUrl',model('HomeMenus')->getMenusUrl());
        WSTConf('CONF',WSTConfig());
    }
}