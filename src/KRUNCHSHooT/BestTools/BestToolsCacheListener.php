<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools;

use pocketmine\event\Listener;
use pocketmine\inventory\PlayerInventory;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\entity\EntityItemPickupEvent;

class BestToolsCacheListener implements Listener {
    
    /** Main $plugin */
    private Main $plugin;
    
    public function __construct($plugin){
        $this->plugin = $plugin;
    }
    
    public function onHeld(PlayerItemHeldEvent $event){
        $this->unvalidate($event->getPlayer());
    }
    
    public function onDrop(PlayerDropItemEvent $event){
        $this->unvalidate($event->getPlayer());
    }
    
    public function onPickup(EntityItemPickupEvent $event){
        if($event->getInventory() instanceof PlayerInventory){
            $this->unvalidate($event->getInventory()->getHolder());
        }
    }
    
    public function unvalidate($player){
        $this->plugin->getPlayerSetting($player)->unvalidate();
    }
}