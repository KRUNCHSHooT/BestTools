<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools\besttols;

use KRUNCHSHooT\BestTools\Main;
use pocketmine\block\Block;
use pocketmine\inventory\PlayerInventory;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\VanillaEnchantments as Enchantment;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class BestToolsHandler
{
    public static function getBestToolFromInventory(Block $block, Player $player, int $currentslot, Item $currentitem): ?int
    {
        $items = self::inventoryToArray($player);

        $bestitem = self::getBestItemStackFromArray($block, $items);
        if($bestitem === null) {
            return self::getNonToolItemFromArray($items, $currentslot, $currentitem, $player);
        }
        return $bestitem;
    }

    public static function getNonToolItemFromArray(array $items, int $currentslot, Item $currentitem, Player $player): ?int
    {
        if(!$currentitem instanceof Tool) {
            return $currentslot;
        }

        $favitem = $player->getInventory()->getItem(self::getFavoriteSlot($player));
        if(!$favitem instanceof Durable){
            return self::getFavoriteSlot($player);
        }

        foreach($items as $slot => $item) {
            if(!$item instanceof Durable) {
                return $slot;
            }
        }
        return null;
    }

    public static function inventoryToArray(Player $player): array
    {
        $inv = $player->getInventory();
        $items = [];
        for($i = 0; $i <= 8; $i++) {
            $items[$i] = $inv->getItem($i);
        }
        return $items;
    }

    public static function getBestItemStackFromArray(Block $block, array $items): ?int
    {
        $list = [];
        foreach($items as $slot => $item) {
            if($item->isNull()) {
                continue;
            }

            if($item instanceof Tool) {
                $eff = self::getMiningEfficiency($block, $item);
                if(!isset($list[$eff]) && $eff !== null) {
                    $list[$eff] = $slot;
                }
            }
        }

        if(empty($list)) {
            return null;
        }

        return $list[max(array_keys($list))];
    }

    public static function getMiningEfficiency(Block $block, Tool $tool): ?float
    {
        $efficiency = null;
        if(($block->getBreakInfo()->getToolType() & $tool->getBlockToolType()) !== 0) {
            if(method_exists($tool, "getTier")) {
                $efficiency = $tool->getTier()->getBaseEfficiency();
            } else {
                $efficiency = $tool->getMiningEfficiency(true);
            }

            if($tool->hasEnchantment(Enchantment::EFFICIENCY())) {
                if(($enchantmentLevel = $tool->getEnchantmentLevel(Enchantment::EFFICIENCY())) > 0) {
                    $efficiency += ($enchantmentLevel ** 2 + 1);
                }
            }
        }

        return is_null($efficiency) ? $efficiency : floor($efficiency);
    }

    public static function getEmptyHotbarSlot(PlayerInventory $inv)
    {
        for($i = 0; $i < 8; $i++) {
            if($inv->getItem($i)->equals(VanillaItems::AIR())) {
                return $i;
            }
        }
        return -1;
    }

    public static function isDamageable(Item $item)
    {
        if($item->equals(VanillaItems::AIR())) {
            return false;
        }

        if($item instanceof Durable) {
            return true;
        } else {
            return false;
        }
    }

    public static function getFavoriteSlot(Player $player){
        $ps = Main::getInstance()->getPlayerManager()->getPlayerSetting($player);
        return $ps->getFavoriteSlot();
    }
}
