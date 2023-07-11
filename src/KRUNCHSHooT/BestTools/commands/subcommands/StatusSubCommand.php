<?php

namespace KRUNCHSHooT\BestTools\commands\subcommands;

use CortexPE\Commando\BaseSubCommand;
use KRUNCHSHooT\BestTools\Main;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class StatusSubCommand extends BaseSubCommand {
    
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
        $message = TextFormat::BOLD . TextFormat::YELLOW . "BestTools Status! " . TextFormat::RESET . "(" . ($ps->isEnable() ? TextFormat::GREEN . "Enabled" : TextFormat::RED . "Disabled") . TextFormat::RESET .")\n" . $ps->getBlacklist()->list() . TextFormat::YELLOW . "Favorite Slot: " . TextFormat::WHITE . strval($ps->getFavoriteSlot());
        $sender->sendMessage($message);
        return;
	}
	
	protected function prepare(): void {
        $this->setPermission("besttools.use");
	}
}