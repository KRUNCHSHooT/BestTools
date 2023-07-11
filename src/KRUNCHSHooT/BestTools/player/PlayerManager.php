<?php

namespace KRUNCHSHooT\BestTools\player;

use KRUNCHSHooT\BestTools\Main;
use KRUNCHSHooT\BestTools\utils\Utils;
use pocketmine\player\Player;
use pocketmine\player\PlayerInfo;

class PlayerManager
{
    /**
     * @var PlayerSetting[]
     */
    private array $playerSettings = [];

    public function __construct()
    {
        Main::getInstance()->getDatabase()->loadPlayers(function (array $rows) {
            foreach ($rows as $row) {
                $this->playerSettings[$row["uuid"]] = new PlayerSetting(boolval($row["besttools_enabled"]), explode(",", $row["blacklist"]), $row["favorite_slot"]);
            }
        });
    }

    public function getPlayerSetting(Player|PlayerInfo $player): ?PlayerSetting
    {
        return $this->playerSettings[(($player instanceof Player) ? Utils::filterUUID($player->getUniqueId()->toString()) : $player->getUuid()->toString())] ?? null;
    }

    public function createPlayerSetting(Player|PlayerInfo $player)
    {
        Main::getInstance()->getDatabase()->registerPlayer($player);
        $this->playerSettings[(($player instanceof Player) ? Utils::filterUUID($player->getUniqueId()->toString()) : $player->getUuid()->toString())] = new PlayerSetting(boolval(Main::getInstance()->getConfig()->getNested("default-settings.besttools-enabled")), [], intval(Main::getInstance()->getConfig()->getNested("default-settings.favorite-slot")));
    }

    /**
     * @return PlayerSetting[]
     */
    public function getPlayerSettings(): array
    {
        return $this->playerSettings;
    }
}
