<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools\player;

use KRUNCHSHooT\BestTools\Main;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\tile\Chest;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\world\sound\XpCollectSound;

class PlayerListener implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        if(Main::getInstance()->getPlayerManager()->getPlayerSetting($player) === null) {
            Main::getInstance()->getPlayerManager()->createPlayerSetting($player);
        }
    }

    /**
     * Summary of onBreak
     * @param BlockBreakEvent $event
     * @return void
     *
     * @priority HIGHEST
     */
    public function onBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        if($event->isCancelled()) {
            return;
        }

        if(boolval(Main::getInstance()->getConfig()->get("fastpick-enabled"))) {
            return;
        }

        if(!$player->hasPermission("besttools.fastpick")) {
            return;
        }

        $ps = Main::getInstance()->getPlayerManager()->getPlayerSetting($player);
        if($ps->getLinked() === null) {
            return;
        }

        if(floor($player->getPosition()->distance($ps->getLinked())) > Main::getInstance()->getMaxdistance() || $ps->getLinked()->getWorld()->getFolderName() !== $player->getPosition()->getWorld()->getFolderName()) {
            return;
        }

        $tile = $player->getWorld()->getTile($ps->getLinked());
        if(!$tile instanceof Chest) {
            $player->sendMessage("§aSomething's Wrong with Chest you linked");
            return;
        }

        $drops = $event->getDrops();
        foreach($drops as $drop) {
            if(!$tile->getInventory()->canAddItem($drop)) {
                $player->sendMessage("§cChest is Full!");
                return;
            }
            $tile->getInventory()->addItem($drop);
        }

        $event->setDrops([]);
        $player->broadcastSound(new XpCollectSound());
    }

    /**
     * Summary of onTouch
     * @param PlayerInteractEvent $event
     * @return void
     */
    public function onTouch(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();

        if(boolval(Main::getInstance()->getConfig()->get("fastpick-enabled"))) {
            return;
        }

        if(!$player->hasPermission("besttools.fastpick")) {
            return;
        }

        $ps = Main::getInstance()->getPlayerManager()->getPlayerSetting($player);
        if(!$ps->cmode) {
            return;
        }

        if($block->getTypeId() !== BlockTypeIds::CHEST) {
            $player->sendMessage("§cBlock Must be a Chest!");
            return;
        }

        $ps->setLinked($block->getPosition());
        $ps->cmode = false;
        $event->cancel();
        $player->sendMessage("§aSuccess Linked Chest");
    }
}
