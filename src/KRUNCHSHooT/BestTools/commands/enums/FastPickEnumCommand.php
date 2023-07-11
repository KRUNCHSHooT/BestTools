<?php

namespace KRUNCHSHooT\BestTools\commands\enums;

use CortexPE\Commando\args\StringEnumArgument;

class FastPickEnumCommand extends StringEnumArgument {
    
	/**
	 * @param string $argument
	 * @param \pocketmine\command\CommandSender $sender
	 * @return mixed
	 */
	public function parse(string $argument, \pocketmine\command\CommandSender $sender): mixed {
        return $argument;
	}
	
	/**
	 * @return string
	 */
	public function getTypeName(): string {
        return "option";
	}

    public function getEnumValues(): array {
		return ["link", "unlink"];
	}
}