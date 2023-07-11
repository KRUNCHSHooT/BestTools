<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools\commands;

use CortexPE\Commando\BaseCommand;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use KRUNCHSHooT\BestTools\commands\subcommands\BlackListSubCommand;
use KRUNCHSHooT\BestTools\commands\subcommands\DisableSubCommand;
use KRUNCHSHooT\BestTools\commands\subcommands\EnableSubCommand;
use KRUNCHSHooT\BestTools\commands\subcommands\FavSlotSubCommand;
use KRUNCHSHooT\BestTools\commands\subcommands\ReloadSubCommand;
use KRUNCHSHooT\BestTools\commands\subcommands\StatusSubCommand;
use KRUNCHSHooT\BestTools\Main;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class BestToolsCommand extends BaseCommand {
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

        $this->MenuForm($sender);
	}

    public function MenuForm(Player $player){
        $ps = Main::getInstance()->getPlayerManager()->getPlayerSetting($player);
        $form = new SimpleForm(function (Player $player, $data = null) {
            if($data === null){
                return;
            }
            
            switch($data){
                case "disable":
                    $player->getServer()->getCommandMap()->dispatch($player, "bt disable");
                    break;
                case "enable":
                    $player->getServer()->getCommandMap()->dispatch($player, "bt enable");
                    break;
                case "blacklist":
                    $this->BLForm($player);
                    break;
                case "favslot":
                    $this->FavSlotForm($player);
                    break;
            }
        });

        $form->setTitle(TextFormat::YELLOW . "BestTools Settings");
        $form->setContent(TextFormat::BOLD . TextFormat::YELLOW . "BestTools Status! " . TextFormat::RESET . "(" . ($ps->isEnable() ? TextFormat::GREEN . "Enabled" : TextFormat::RED . "Disabled") . TextFormat::RESET . ")\n" . $ps->getBlacklist()->list() . TextFormat::YELLOW . "Favorite Slot: " . TextFormat::WHITE . strval($ps->getFavoriteSlot()));
        if($ps->isEnable()){
            $form->addButton(TextFormat::RED . "Disable", label: "disable");
        } else {
            $form->addButton(TextFormat::GREEN . "Enable", label: "enable");
        }
        $form->addButton("Blacklist Manager", label: "blacklist");
        $form->addButton("Favorite Slot", label: "favslot");
        $player->sendForm($form);
    }

    public function BLForm(Player $player){
        $form = new SimpleForm(function (Player $player, $data = null) {
            if($data === null){
                return;
            }
            if($data == "back"){
                $this->MenuForm($player);
            } else {
                $this->BLOptionForm($player, $data);
            }
        });
        $form->setTitle("BlackList Manager");
        $form->addButton("Add", label: "add");
        $form->addButton("Remove", label: "remove");
        $form->addButton("Back", label: "back");
        $player->sendForm($form);
    }

    public function BLOptionForm(Player $player, string $option){
        $form = new CustomForm(function(Player $player, $data = null) use ($option) {
            if($data === null){
                return;
            }
            $player->getServer()->getCommandMap()->dispatch($player, "bt blacklist " . $option . " " . $data["block"]);
        });
        $form->setTitle(ucfirst($option) . " Manager");
        $form->addInput("Block Name", label: "block");
        $player->sendForm($form);
    }

    public function FavSlotForm(Player $player){
        $form = new CustomForm(function (Player $player, $data = null) {
            if($data === null){
                return;
            }

            $ps = Main::getInstance()->getPlayerManager()->getPlayerSetting($player);
            $status = $ps->setFavoriteSlot(intval($data["slot"]));
            if($status) {
                $player->sendMessage("Successfuly Change Favorite Slot to " . strval($data["slot"]));
            } else {
                $player->sendMessage("slot must be in range 0 to 8 (as a hotbar slot)");
            }
        });
        $form->setTitle("Favorite Slot Manager");
        $form->addSlider("Hotbar Slot", 0, 8, 1, 0, "slot");
        $player->sendForm($form);
    }
	
	protected function prepare(): void {
        $this->setPermission("besttools.use");
        $this->registerSubCommand(new EnableSubCommand("enable", "To enable BestTools Features"));
        $this->registerSubCommand(new DisableSubCommand("disable", "To disable BestTools Features"));
        $this->registerSubCommand(new StatusSubCommand("status", "To see a status of your BestTools Settings"));
        $this->registerSubCommand(new BlackListSubCommand("blacklist", "To do something with blacklist feature", ["bt"]));
        $this->registerSubCommand(new FavSlotSubCommand("favorite-slot", "To set your favorite slot when that is not tools/weapons compatible found"));
        $this->registerSubCommand(new ReloadSubCommand("reload"));
	}
}
