<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools\commands;

use KRUNCHSHooT\BestTools\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\player\Player;

class FastPickCommand extends Command implements PluginOwned {
    
    public Main $plugin;
    
    public function __construct(Main $plugin){
        
        $this->plugin = $plugin;
        
        parent::__construct("fastpick", 'to turn BestTools Fast Pickup link and unlink.', "/fastpick [link or unlink]", ["fpu"]);
        
        $this->setPermission("besttools.fastpick");
    }
    
    public function execute(CommandSender $sender, string $label, array $args){
        
        if(!$this->testPermission($sender)) return;
        
        if(!$sender instanceof Player){
            $sender->sendMessage("Hi Console!");
            return;
        }
        
        $ps = $this->plugin->getPlayerSetting($sender);
        if(!isset($args[0])){
            $sender->sendMessage("§l§eBestTools §dFastPick §eStats!\n§rstatus: " . $ps->getFPStats() . "\n§e/fpu help - to view more info for this command");
            return;
        }
        
        switch(strtolower($args[0])){
            case "help":
                $sender->sendMessage("§e[ BestTools Fast Pickup Arguments ] \n§f/fpu link - to link the chest you want to store\n§f/fpu unlink - to unlink Chest");
                break;
            case "link":
                $ps->cmode = true;
                $sender->sendMessage("§f[§cBestTools§f] §eClick the chest you want to store\n§atype /fpu unlink - to cancel action or unlink chest");
                break;
            case "unlink":
                if($ps->cmode){
                    $ps->cmode = false;
                    $sender->sendMessage("§f[§cBestTools§f] §eSuccess to cancel action!");
                    return;
                }
                $ps->setLinked();
                $sender->sendMessage("§f[§cBestTools§f] §eSuccess to unlink chest!");
                break;
        }
        return;
    }
    
    public function getOwningPlugin(): Main
    {
        return $this->plugin;
    }
}