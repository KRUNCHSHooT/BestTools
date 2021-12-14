<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools\commands;

use KRUNCHSHooT\BestTools\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\player\Player;

class BestToolsCommand extends Command implements PluginOwned {
    
    public Main $plugin;
    
    public function __construct(Main $plugin){
        
        $this->plugin = $plugin;
        
        parent::__construct("besttools", 'to turn BestTools on and off.', "/besttools [enable or disable]", ["bt"]);
        
        $this->setPermission("besttools.use");
    }
    
    public function execute(CommandSender $sender, string $label, array $args){
        
        if(!$this->testPermission($sender)) return;
        
        if(!$sender instanceof Player){
            $sender->sendMessage("Hi Console!");
            return;
        }
                
        if(!isset($args[0])){
            $ps = $this->plugin->getPlayerSetting($sender);
            $sender->sendMessage("§l§eBestTools Stats!\n§rstatus: " . ($ps->enable ? "§aON" : "§cOFF") . "\n§e/besttools help - to view more info for this command");
            return;
        }
                
        switch(strtolower($args[0])){
            case "help":
                $sender->sendMessage("§e[ BestTools Arguments ] \n§f/bt enable - to enable BestTools\n§f/bt disable - to disable BestTools");
                break;
            case "enable":
                $ps = $this->plugin->getPlayerSetting($sender);
                $ps->enable = true;
                $sender->sendMessage("§f[§cBestTools§f] §eBestTools Has Been Enabled!");
                break;
            case "disable":
                $ps = $this->plugin->getPlayerSetting($sender);
                $ps->enable = false;
                $sender->sendMessage("§f[§cBestTools§f] §eBestTools Has Been Disabled!");
                break;
        }
        return;
    }
    
    public function getOwningPlugin(): Main
    {
        return $this->plugin;
    }
}
