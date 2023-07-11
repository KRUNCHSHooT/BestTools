<?php

namespace KRUNCHSHooT\BestTools\commands\subcommands;

use CortexPE\Commando\BaseSubCommand;
use KRUNCHSHooT\BestTools\commands\enums\BlocksEnumCommand;
use KRUNCHSHooT\BestTools\commands\subcommands\BlackListenum\OptionEnumCommand;
use KRUNCHSHooT\BestTools\Main;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\player\Player;

class BlackListSubCommand extends BaseSubCommand {

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
        switch($args["option"]){
            case "add":
                /**
                 * @var Item|null $block
                 */
                $block = $args["block"];
                if($block === null){
                    $sender->sendMessage("Block Not Found");
                    return;
                } elseif($block->getBlock()->getTypeId() === VanillaBlocks::AIR()->getTypeId()){
                    $sender->sendMessage("That is not a Block");
                    return;
                } else {
                    $ps->getBlacklist()->add($block->getBlock());
                    $sender->sendMessage($block->getBlock()->getName() . " has been added");
                    return;
                }
            case "remove":
                /**
                 * @var Item|null $block
                 */
                $block = $args["block"];
                if($block === null){
                    $sender->sendMessage("Block Not Found");
                    return;
                } elseif($block->getBlock()->getTypeId() === VanillaBlocks::AIR()->getTypeId()){
                    $sender->sendMessage("That is not a Block");
                    return;
                } else {
                    $status = $ps->getBlacklist()->remove($block->getBlock());
                    if($status){
                        $sender->sendMessage($block->getBlock()->getName() . " has been removed");
                    } else {
                        $sender->sendMessage($block->getBlock()->getName() . " not found in your blacklist");
                    }
                    return;
                }
            default:
                $sender->sendMessage($this->getUsageMessage());
                break;
        }
	}
	
	protected function prepare(): void {
        $this->setPermission("besttools.blacklist");
        $this->registerArgument(0, new OptionEnumCommand("option"));
        $this->registerArgument(1, new BlocksEnumCommand("block"));
	}
}