<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools;

use pocketmine\block\tile\Chest;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\world\sound\XpCollectSound;

class PlayerListener implements Listener {
   
   /** Main $plugin */
   private Main $plugin;
   
   public function __construct($plugin){
       $this->plugin = $plugin;
   }
   
   public function onQuit(PlayerQuitEvent $event){
       $player = $event->getPlayer();
       if(!isset($this->plugin->playerSettings[str_replace("-", "", $player->getUniqueId()->toString())])) return;
       $ps = $this->plugin->getPlayerSetting($player);
       $ps->save($this->plugin->getDataFolder() . "besttools.db", str_replace("-", "", $player->getUniqueId()->toString()));
       unset($this->plugin->playerSettings[str_replace("-", "", $player->getUniqueId()->toString())]);
   }
   
   public function onBreak(BlockBreakEvent $event){
       $player = $event->getPlayer();
       if($event->isCancelled()) return;
       if(!$player->hasPermission("besttools.fastpick")){
            return;
       }
       $ps = $this->plugin->getPlayerSetting($player);
       if($ps->getLinked() === null){
           return;
       }
       if(floor($player->getPosition()->distance($ps->getLinked())) > $this->plugin->maxdistance){
           return;
       }
       $tile = $player->getWorld()->getTile($ps->getLinked());
       if(!$tile instanceof Chest){
           $player->sendMessage("§aSomething's Wrong with Chest you linked");
           return;
       }
       $drops = $event->getDrops();
       foreach($drops as $drop){
           if(!$tile->getInventory()->canAddItem($drop)){
               $player->sendMessage("§cChest is Full!");
               return;
           }
           $tile->getInventory()->addItem($drop);
       }
       $event->setDrops([]);
       $player->broadcastSound(new XpCollectSound());
   }
   
   public function onTouch(PlayerInteractEvent $event){
       $player = $event->getPlayer();
       $block = $event->getBlock();
       if(!$player->hasPermission("besttools.fastpick")){
            return;
       }
       $ps = $this->plugin->getPlayerSetting($player);
       if(!$ps->cmode) return;
       if($block->getId() !== 54){
           $player->sendMessage("§cBlock Must be a Chest!");
           return;
       }
       $ps->setLinked($block->getPosition()->asVector3());
       $ps->cmode = false;
       $event->cancel();
       $player->sendMessage("§aSuccess Linked Chest");
   }
}
