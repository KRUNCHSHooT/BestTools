<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools;

use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use SQLite3;

class PlayerSetting {
    /** bool $enable */
    public $enable = false;
    /** Blacklist $bl */
    private $bl;
    /** int[] $btcache */
    public $btcache = [];
    /** ?Vector3 $link */
    public $link = null;
    /** bool $cmode */
    public $cmode = false;
    
    public function __construct($enable, $bl = []){
        $this->enable = $enable;
        $this->bl = new Blacklist($bl);
    }
    
    public function getBlacklist(){
        return $this->bl;
    }
    
    public function isEnable(){
        return $this->enable;
    }
    
    public function getLinked(){
        return $this->link;
    }
    
    public function setLinked(?Vector3 $v = null){
        $this->link = $v;
    }
    
    public function getFPStats(){
        if($this->link !== null){
            return "§aLinked!, §eCoordinate: (" . $this->link->x . "," . $this->link->y . "," . $this->link->z . ")";
        }
        return "§cOFF";
    }
    
    public function unvalidate(){
        $this->btcache = [];
    }
    
    public function validate($block){
        $this->btcache = [];
        $this->btcache[] = $block->getId();
        $this->btcache[] = $block->getMeta();
    }
    
    public function valid($block){
        if(empty($this->btcahce)) return false;
        if($block->getId() == $this->btcache[0] && $block->getMeta() == $this->btcache[1]){
            return true;
        }
        return false;
    }
    
    public function save($loc, $uuid){
        $db = new SQLite3($loc);
        $db->exec("UPDATE besttools SET besttools_use = " . intval($this->enable) . ", blacklist = '" . SQLite3::escapeString($this->bl->toString()) . "' WHERE uuid = '" . SQLite3::escapeString($uuid) . "'");
        $db->close();
    }
}