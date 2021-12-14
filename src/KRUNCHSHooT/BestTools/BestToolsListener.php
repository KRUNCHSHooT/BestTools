<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class BestToolsListener implements Listener {
    
    /** Main $plugin */
    private Main $plugin;
    
    public function __construct($plugin){
        $this->plugin = $plugin;
    }
    
    public function onTouch(PlayerInteractEvent $event){
        $ps = $this->plugin->getPlayerSetting($event->getPlayer());
        if($ps->valid($event->getBlock())){
            return;
        }
        
        $player = $event->getPlayer();
        if(!$player->hasPermission("besttools.use")) return;
        
        if(!$ps->isEnable()) return;
        
        $block = $event->getBlock();
        if($ps->getBlacklist()->contains($block)) return;
        
        $inv = $player->getInventory();
        if ($event->getAction() != PlayerInteractEvent::LEFT_CLICK_BLOCK) return;
        
        $besttool = BestToolsHandler::getBestToolFromInventory($block, $player, $inv->getHeldItemIndex(), $inv->getItemInHand());
        
        if($besttool === null || $besttool === $inv->getHeldItemIndex() || $besttool < 0){
            $ps->validate($block);
            return;
        }
        $inv->setHeldItemIndex($besttool);
        $ps->validate($block);
    }
}