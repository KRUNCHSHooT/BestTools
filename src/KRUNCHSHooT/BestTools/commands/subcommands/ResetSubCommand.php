<?php


namespace KRUNCHSHooT\BestTools\commands\subcommands;

use CortexPE\Commando\BaseSubCommand;
use KRUNCHSHooT\BestTools\Main;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class ResetSubCommand extends BaseSubCommand {
    
	/**
	 * @param CommandSender $sender
	 * @param string $aliasUsed
	 * @param array $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        Main::getInstance()->reset();
        $sender->sendMessage("BestTools Player Data has been reset");
        return;
	}
	
	protected function prepare(): void {
        $this->setPermission("besttools.use");
	}
}