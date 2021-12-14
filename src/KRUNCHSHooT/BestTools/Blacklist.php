<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools;

use pocketmine\item\ItemFactory;

class Blacklist {
    
    public $mats = [];
    
    public function __construct($bl){
        for($p = 0; $p < count($bl); $p++){
            $ex = explode(":", $bl[$p]);
            if(count($ex) != 2) continue;
            $this->mats[$p]["id"] = $ex[0];
            $this->mats[$p]["meta"] = $ex[1];
        }
    }
    
    public function contains($block){
        $stats = false;
        list($id, $meta) = [$block->getId(), $block->getMeta()];
        for($p = 0; $p < count($this->mats); $p++){
            if($this->mats[$p]["id"] == $id && $this->mats[$p]["meta"] == $meta){
                $stats = true;
            }
        }
        return $stats;
    }
    
    public function add($id, $meta){
        $p = count($this->mats);
        $this->mats[$p]["id"] = $id;
        $this->mats[$p]["meta"] = $meta;
    }
    
    public function remove($id, $meta){
        for($p = 0; $p < count($this->mats); $p++){
            if($this->mats[$p]["id"] == $id && $this->mats[$p]["meta"] == $meta){
                unset($this->mats[$p]);
                break;
            }
        }
    }
    
    public function list(){
        $list = "§eList Blacklist\n§f";
        foreach($this->mats as $p => $val){
            $list .= "- " . (ItemFactory::getInstance()->get($val["id"], $val["meta"]))->getName() . "\n";
        }
        return $list;
    }
    
    public function toString(){
        $string = "";
        foreach($this->mats as $p => $val){
            $string .= $val["id"] . ":" . $val["meta"] . ",";
        }
        $string = substr($string, 0, -1);
        return $string;
    }
    
    public function clear(){
        $this->mats = [];
    }
}