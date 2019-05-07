<?php
namespace card;
class Card{

    var $set = array();
    var $rulekey = array("str"=>"\@", "num"=>"\#", "full"=>"\*");
    var $sysrule = '';
    var $rule = '';
    var $rulemap_str = "ABCDEFGHIJKLMNPQRSTUVWXYZ";
    var $rulemap_num = "123456789";

    var $rulereturn = array();
    var $cardlist = array();

    var $succeed = 0;
    var $fail = 0;
    var $failmin = 1;
    var $failrate = '0.1';


    function card() {
        $this->init();
    }

    function init() {
        $this->set = 'DZ2019@@@@@#####*****';
        $this->sysrule = "^[A-Z0-9".implode('|', $this->rulekey)."]+$";
    }

    function make($rule = '', $num = 1) {
        $this->rule = empty($rule) ? $this->set['rule'] : trim($rule) ;
        if(empty($this->rule)) {
            return -1;
        }
        $this->fail($num);
        for($i = 0; $i < $num ; $i++) {
            if($this->checkrule($this->rule)) {
                $card = $this->rule;
                foreach($this->rulereturn AS $key => $val) {
                    $search = array();
                    foreach($val AS $skey => $sval) {
                        $search[] = '/'.$this->rulekey[$key].'/';
                    }
                    $card =  preg_replace($search, $val, $card, 1);
                }
            } else {
                return 0;
            }
            array_push($this->cardlist,['cardNo'=>$card,'cardPwd'=>$this->randomPassword()]);
        }
        return true;
    }

    function randomPassword( $length = 8 )
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $length = rand(10, 16);
        $password = substr( str_shuffle(sha1(rand() . time()) . $chars ), 0, $length );
        return $password;
    }

    function checkrule($rule, $type = '0') {
        if(!preg_match("/($this->sysrule)/i", $rule)){
            return -2;
        }
        if($type == 0) {
            foreach($this->rulekey AS $key => $val) {
                $match = array();
                preg_match_all("/($val){1}/i", $rule, $match);
                $number[$key] = count($match[0]);
                if($number[$key] > 0) {
                    for($i = 0; $i < $number[$key]; $i++) {
                        switch($key) {
                            case 'str':
                                $rand = mt_rand(0, (strlen($this->rulemap_str) - 1));
                                $this->rulereturn[$key][$i] = $this->rulemap_str[$rand];
                                break;
                            case 'num':
                                $rand = mt_rand(0, (strlen($this->rulemap_num) - 1));
                                $this->rulereturn[$key][$i] = $this->rulemap_num[$rand];
                                break;
                            case 'full':
                                $fullstr = $this->rulemap_str.$this->rulemap_num;
                                $rand = mt_rand(0,(strlen($fullstr) - 1));
                                $this->rulereturn[$key][$i] = $fullstr[$rand];
                                break;
                        }
                    }
                }
            }
        }
        return true;

    }

    function fail($num = 1) {
        $failrate = $this->failrate ? (float)$this->failrate : '0.1';
        $this->failmin = ceil($num * $failrate);
        $this->failmin = $this->failmin > 100 ? 100 : $this->failmin;
    }
}