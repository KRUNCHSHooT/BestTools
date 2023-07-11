<?php

namespace KRUNCHSHooT\BestTools\commands\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseSubCommand;
use KRUNCHSHooT\BestTools\Main;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class FavSlotSubCommand extends BaseSubCommand {

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
        $status = $ps->setFavoriteSlot($args["slot"]);
        if($status){
            $sender->sendMessage("Successfuly Change Favorite Slot to " . strval($args["slot"]));
        } else {
            $sender->sendMessage("slot must be in range 0 to 8 (as a hotbar slot)");
        }
	}
	
	protected function prepare(): void {
        $this->setPermission("besttools.use");
        $this->registerArgument(0, new IntegerArgument("slot"));
	}
}