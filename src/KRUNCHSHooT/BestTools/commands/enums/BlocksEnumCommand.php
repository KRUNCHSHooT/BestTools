<?php

namespace KRUNCHSHooT\BestTools\commands\enums;

use CortexPE\Commando\args\StringEnumArgument;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\LegacyStringToItemParserException;
use pocketmine\item\StringToItemParser;

class BlocksEnumCommand extends StringEnumArgument {
    
	/**
	 * @param string $argument
	 * @param CommandSender $sender
	 * @return mixed
	 */
	public function parse(string $argument, CommandSender $sender): mixed {
        try{
			return StringToItemParser::getInstance()->parse($argument) ?? LegacyStringToItemParser::getInstance()->parse($argument);
		}catch(LegacyStringToItemParserException $e){
			return null;
		}
	}
	
	/**
	 * @return string
	 */
	public function getTypeName(): string {
        return "block";
	}

    public function getEnumValues(): array {
		$filtered = array_filter(StringToItemParser::getInstance()->getKnownAliases(), function ($alias, $key) {
			/**
			 * @var Item $item
			 */
			$item = StringToItemParser::getInstance()->parse($alias);
			return $item->getBlock()->getTypeId() !== VanillaBlocks::AIR()->getTypeId();
		}, ARRAY_FILTER_USE_BOTH);
		return array_values($filtered);
	}
}