<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools\commands;

use CortexPE\Commando\BaseCommand;
use KRUNCHSHooT\BestTools\commands\enums\FastPickEnumCommand;
use KRUNCHSHooT\BestTools\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\player\Player;

class FastPickCommand extends BaseCommand {
    
	/**
	 * @param CommandSender $sender
	 * @param string $aliasUsed
	 * @param array $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if(!$sender instanceof Player){
            $sender->sendMessage("Hi Console!");
            return;
        }
        
        $ps = $this->plugin->getPlayerManager()->getPlayerSetting($sender);
        if(!isset($args["option"])){
            $sender->sendMessage("§l§eBestTools §dFastPick §eStats!\n§rstatus: " . $ps->getFPStats() . "\n§e/fpu help - to view more info for this command");
            return;
        }
        
        switch(strtolower($args["option"])){
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
    
	protected function prepare(): void {
        $this->setPermission("besttools.fastpick");
        $this->registerArgument(0, new FastPickEnumCommand("option"));
	}
}