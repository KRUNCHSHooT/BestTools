<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

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
}
