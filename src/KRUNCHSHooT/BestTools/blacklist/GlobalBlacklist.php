<?php

namespace KRUNCHSHooT\BestTools\blacklist;

class GlobalBlacklist extends Blacklist {

    public function list()
    {
        $list = "§eList Global Blacklist\n§f";
        foreach($this->mats as $p => $val) {
            $list .= "- " . $val["display"] . "\n";
        }
        return $list;
    }
}