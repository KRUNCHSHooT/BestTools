<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools\besttols;

use KRUNCHSHooT\BestTools\Main;
use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\VanillaItems;
use pocketmine\player\GameMode;
use pocketmine\player\Player;

class BestToolsListener implements Listener
{
    /**
     * Summary of onTouch
     * @param PlayerInteractEvent $event
     * @return void
     * 
     * @priority MONITOR
     */
    public function onTouch(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $ps = Main::getInstance()->getPlayerManager()->getPlayerSetting($player);
        if($ps->valid($block)) {
            return;
        }

        if(!$player->hasPermission("besttools.use")) {
            return;
        }

        if(!$ps->isEnable()) {
            return;
        }

        if(Main::getInstance()->getGlobalBlacklist()->contains($block) || $ps->getBlacklist()->contains($block)) {
            return;
        }

        if(boolval(Main::getInstance()->getConfig()->get("survival-only")) && !$player->getGamemode()->equals(GameMode::SURVIVAL())){
            return;
        }

        $inv = $player->getInventory();
        if ($event->getAction() != PlayerInteractEvent::LEFT_CLICK_BLOCK) {
            return;
        }

        $besttool = BestToolsHandler::getBestToolFromInventory($block, $player, $inv->getHeldItemIndex(), $inv->getItemInHand());

        if($besttool === null || $besttool === $inv->getHeldItemIndex() || $besttool < 0) {
            $ps->validate($block);
            return;
        }
        $inv->setHeldItemIndex($besttool);
        $ps->validate($block);
    }
}