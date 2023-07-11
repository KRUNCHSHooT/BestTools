<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools\besttols;

use KRUNCHSHooT\BestTools\Main;
use pocketmine\event\Listener;
use pocketmine\inventory\PlayerInventory;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\entity\EntityItemPickupEvent;

class BestToolsCacheListener implements Listener
{
    /**
     * Summary of onHeld
     * @param PlayerItemHeldEvent $event
     * @return void
     */
    public function onHeld(PlayerItemHeldEvent $event)
    {
        $this->unvalidate($event->getPlayer());
    }

    /**
     * Summary of onDrop
     * @param PlayerDropItemEvent $event
     * @return void
     */
    public function onDrop(PlayerDropItemEvent $event)
    {
        $this->unvalidate($event->getPlayer());
    }

    /**
     * Summary of onPickup
     * @param EntityItemPickupEvent $event
     * @return void
     */
    public function onPickup(EntityItemPickupEvent $event)
    {
        if($event->getInventory() instanceof PlayerInventory) {
            $this->unvalidate($event->getInventory()->getHolder());
        }
    }

    public function unvalidate($player)
    {
        Main::getInstance()->getPlayerManager()->getPlayerSetting($player)?->unvalidate();
    }
}
