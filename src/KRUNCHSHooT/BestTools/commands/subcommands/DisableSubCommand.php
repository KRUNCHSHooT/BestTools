<?php


namespace KRUNCHSHooT\BestTools\commands\subcommands;

use CortexPE\Commando\BaseSubCommand;
use KRUNCHSHooT\BestTools\Main;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class DisableSubCommand extends BaseSubCommand {
    
	/**
	 * @param CommandSender $sender
	 * @param string $aliasUsed
	 * @param array $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if(!$sender instanceof Player){
            $sender->sendMessage("Please Use this In-Game!");
            return;
        }

        $ps = Main::getInstance()->getPlayerManager()->getPlayerSetting($sender);
        if(!$ps->isEnable()){
            $sender->sendMessage("BestTools is Already disabled");
            return;
        }
        $ps->setEnable(false);
        $sender->sendMessage("BestTools has been disabled");
        return;
	}
	
	protected function prepare(): void {
        $this->setPermission("besttools.use");
	}
}