<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools\commands;

use KRUNCHSHooT\BestTools\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\player\Player;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\LegacyStringToItemParserException;
use pocketmine\item\StringToItemParser;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\utils\TextFormat;

class BlacklistCommand extends Command implements PluginOwned {
    
    public Main $plugin;
    
    public function __construct(Main $plugin){
        
        $this->plugin = $plugin;
        
        parent::__construct("blacklist", 'to enter a list of blocks to be blacklisted and clean it up.', "/blacklist [id/name and clear/reset]", ["bl"]);
        
        $this->setPermission("besttools.blacklist");
    }
    
    public function execute(CommandSender $sender, string $label, array $args){
        
        if(!$this->testPermission($sender)) return;
        
        if(!$sender instanceof Player){
            $sender->sendMessage("Hi Console!");
            return;
        }
        
        $ps = $this->plugin->getPlayerSetting($sender);
        if(!isset($args[0])){
            $sender->sendMessage($ps->getBlacklist()->list() . "\n§e/blacklist help - to view more argument");
            return;
        }
        
        switch($args[0]){
            case "help":
                $sender->sendMessage("§e[ Blacklist Arguments ] \n§f/bl add - to add block you are holding to blacklist\n§f/bl add <item> - to add block you want to blacklist\n§f/bl remove - to remove block you are holding from blacklist\n§f/bl remove <item> - to remove block you want from blacklist\n§f/bl clear - to reset all yours on blacklist");
                break;
            case "add":
                if(isset($args[1])){
                    try{
	                    $block = StringToItemParser::getInstance()->parse($args[1]) ?? LegacyStringToItemParser::getInstance()->parse($args[1]);
		            }catch(LegacyStringToItemParserException $e){
	                    $sender->sendMessage(KnownTranslationFactory::commands_give_item_notFound($args[1])->prefix(TextFormat::RED));
			            return;
	                }
                } else {
                    $block = $sender->getInventory()->getItemInHand();
                }
		        
		        $ps->getBlacklist()->add($block->getId(), $block->getMeta());
	            $sender->sendMessage("§f[§cBestTools§f] §e" . $block->getName() . " has been added to Blacklist");
                break;
            case "remove":
                if(isset($args[1])){
                    try{
	                    $block = StringToItemParser::getInstance()->parse($args[1]) ?? LegacyStringToItemParser::getInstance()->parse($args[1]);
		            }catch(LegacyStringToItemParserException $e){
	                    $sender->sendMessage(KnownTranslationFactory::commands_give_item_notFound($args[1])->prefix(TextFormat::RED));
			            return;
	                }
                } else {
                    $block = $sender->getInventory()->getItemInHand();
                }
	            
                $ps->getBlacklist()->remove($block->getId(), $block->getMeta());
	            $sender->sendMessage("§f[§cBestTools§f] §e" . $block->getName() . " has been removed from Blacklist");
                break;
            case "clear":
            case "reset":
                $ps->getBlacklist()->clear();
                $sender->sendMessage("§f[§cBestTools§f] §eBlacklist has been Cleared");
                break;
        }
	    return;
    }
    
    public function getOwningPlugin(): Main
    {
        return $this->plugin;
    }
}