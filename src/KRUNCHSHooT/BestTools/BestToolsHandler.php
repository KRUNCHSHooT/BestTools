<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools;

use pocketmine\item\Tool;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\VanillaEnchantments as Enchantment;

class BestToolsHandler {
    
    public static function getBestToolFromInventory($block, $player, $currentslot, $currentitem){
        $items = self::inventoryToArray($player);
        
        $bestitem = self::getBestItemStackFromArray($block, $items);
        if($bestitem === null){
            return self::getNonToolItemFromArray($items, $currentslot, $currentitem);
        }
        return $bestitem;
    }
    
    public static function getNonToolItemFromArray($items, $currentslot, $currentitem){
        if(!$currentitem instanceof Tool){
            return $currentslot;
        }
        
        foreach($items as $slot => $item){
            if(!$item instanceof Durable){
                var_dump($slot);
                return $slot;
            }
        }
        return null;
    }
    
    public static function inventoryToArray($player) {
        $inv = $player->getInventory();
        $items = [];
        for($i = 0; $i <= 8; $i++) {
            $items[$i] = $inv->getItem($i);
        }
        return $items;
    }
    
    public static function getBestItemStackFromArray($block, $items){
        $list = [];
        foreach($items as $slot => $item){
            if($item === null) continue;
            
            if($item instanceof Tool){
                $eff = self::getMiningEfficiency($block, $item);
                if(!isset($list[$eff]) && $eff !== null){
                    $list[$eff] = $slot;
                }
            }
        }
        
        if(empty($list)) return null;
        
        return $list[max(array_keys($list))];
    }
    
    public static function getMiningEfficiency($block, $tool){
		$efficiency = null;
		if(($block->getBreakInfo()->getToolType() & $tool->getBlockToolType()) !== 0){
			if(method_exists($tool, "getTier")) {
			    $efficiency = $tool->getTier()->getBaseEfficiency();
			} else {
			    $efficiency = $tool->getMiningEfficiency(true);
			}
			if($tool->hasEnchantment(Enchantment::EFFICIENCY())){
			    if(($enchantmentLevel = $tool->getEnchantmentLevel(Enchantment::EFFICIENCY())) > 0){
				    $efficiency += ($enchantmentLevel ** 2 + 1);
			    }
			}
		}

		return is_null($efficiency) ? $efficiency : floor($efficiency);
	}
}