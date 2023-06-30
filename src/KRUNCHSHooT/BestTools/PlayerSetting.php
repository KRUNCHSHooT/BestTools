<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools;

use pocketmine\block\Block;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\world\Position;
use SQLite3;

class PlayerSetting {
    /** bool $enable */
    public $enable = false;
    /** Blacklist $bl */
    private $bl;
    /** int $btcache */
    public int $btcache = 0;
    /** ?Position $link */
    public $link = null;
    /** bool $cmode */
    public $cmode = false;
    
    public function __construct($enable, $bl = []){
        $this->enable = $enable;
        $this->bl = new Blacklist($bl);
    }
    
    public function getBlacklist() : Blacklist {
        return $this->bl;
    }
    
    public function isEnable() : bool {
        return $this->enable;
    }
    
    public function getLinked() : ?Position {
        return $this->link;
    }
    
    public function setLinked(?Position $v = null){
        $this->link = $v;
    }
    
    public function getFPStats() : string {
        if($this->link !== null){
            return "§aLinked!, §eCoordinate: (" . $this->link->x . "," . $this->link->y . "," . $this->link->z . ")";
        }
        return "§cOFF";
    }
    
    public function unvalidate() : void {
        $this->btcache = 0;
    }
    
    public function validate(Block $block) : void {
        $this->btcache = $block->getTypeId();
    }
    
    public function valid(Block $block) : bool {
        if(empty($this->btcache)) return false;
        if($block->getTypeId() == $this->btcache){
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