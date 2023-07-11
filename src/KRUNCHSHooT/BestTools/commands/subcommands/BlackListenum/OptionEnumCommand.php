<?php

namespace KRUNCHSHooT\BestTools\commands\subcommands\BlackListenum;

use CortexPE\Commando\args\StringEnumArgument;
use pocketmine\command\CommandSender;

class OptionEnumCommand extends StringEnumArgument {
    
    /**
	 * @param string $argument
	 * @param CommandSender $sender
	 * @return mixed
	 */
	public function parse(string $argument, CommandSender $sender): mixed {
        return $argument;
	}
	
	/**
	 * @return string
	 */
	public function getTypeName(): string {
        return "options";
	}

    public function getEnumValues(): array {
		return ["add", "remove"];
	}
}