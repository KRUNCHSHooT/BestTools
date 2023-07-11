<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools\blacklist;

use KRUNCHSHooT\BestTools\Main;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\StringToItemParser;

class Blacklist
{
    public array $mats = [];

    public function __construct($bl)
    {
        foreach($bl as $alias) {
            if($alias == "") continue;
            /**
             * @var StringToItemParser $parser
             */
            $parser = StringToItemParser::getInstance();
            $item = $parser->parse($alias);
            if(is_null($item)) {
                Main::getInstance()->getLogger()->info("Item named by " . $alias . " has not registered at Parser");
                continue;
            } elseif($item->getBlock()->hasSameTypeId(VanillaBlocks::AIR())) {
                // log that the block is an item or maybe an air block when use getBlock to get the Block (am i need this?)
                continue;
            } elseif(isset($this->mats[$item->getBlock()->getTypeId()])){
                continue;
            }
            $this->mats[$item->getBlock()->getTypeId()] = ["display" => $item->getBlock()->getName(), "alias" => $parser->lookupBlockAliases($item->getBlock())];
        }
    }

    public function contains(Block $block)
    {
        if(isset($this->mats[$block->getTypeId()])) {
            return true;
        }
        return false;
    }

    public function add(Block $block)
    {
        $this->mats[$block->getTypeId()] = ["display" => $block->getName(), "alias" => StringToItemParser::getInstance()->lookupBlockAliases($block)];
    }

    public function remove(Block $block): bool
    {
        if(isset($this->mats[$block->getTypeId()])) {
            unset($this->mats[$block->getTypeId()]);
            return true;
        }
        return false;
    }

    public function list()
    {
        $list = "§eList Blacklist\n§f";
        foreach($this->mats as $p => $val) {
            $list .= "- " . $val["display"] . "\n";
        }
        return $list;
    }

    public function toString()
    {
        $string = "";
        foreach($this->mats as $p => $val) {
            foreach($val["alias"] as $alias) {
                $string .= $alias . ",";
            }
        }
        $string = substr($string, 0, -1);
        return $string;
    }

    public function clear()
    {
        $this->mats = [];
    }
}
