<?php
namespace elexiangmart\home\controller;

use card\Card;

class Test extends Base{
    public function demo1()
    {
        $card = new Card();
        $checkrule = $card->checkrule('DZ2019@@@@@#####*****', 1);

        if($checkrule === -2) {
            die('生成规则有误');
        }

        $makenum = 500;
        $cardval = array();
        $card->make('DZ2019@@@@@#####*****', $makenum, $cardval);
        echo '<pre>';
        print_r($card->cardlist);
	}
}
