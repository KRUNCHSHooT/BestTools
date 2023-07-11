<?php

namespace KRUNCHSHooT\BestTools\database;

use Closure;
use KRUNCHSHooT\BestTools\blacklist\Blacklist;
use KRUNCHSHooT\BestTools\Main;
use KRUNCHSHooT\BestTools\player\PlayerSetting;
use KRUNCHSHooT\BestTools\utils\Utils;
use pocketmine\player\Player;
use pocketmine\player\PlayerInfo;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;

class Database
{
    private const INIT = "besttools.init";
    private const REGISTER = "besttools.register";
    private const LOAD = "besttools.loadPlayers";
    private const UPDATE = "besttools.update";
    private const RESET = "besttools.reset";

    private DataConnector $database;

    public function __construct(Main $plugin)
    {
        $this->database = libasynql::create($plugin, $plugin->getConfig()->get("database"), [
            "sqlite" => "sqlite.sql",
            "mysql" => "mysql.sql"
        ]);
        $this->database->executeGeneric(self::INIT);
        $this->database->waitAll();
    }

    /**
     * Summary of registerPlayer
     * @param Player $player
     * @return void
     */
    public function registerPlayer(Player|PlayerInfo $player)
    {
        $this->database->executeInsert(self::REGISTER, ["uuid" => Utils::filterUUID(($player instanceof Player) ? $player->getUniqueId()->toString() : $player->getUuid()->toString()), "enabled" => boolval(Main::getInstance()->getConfig()->getNested("default-settings.besttools-enabled")), "blacklist" => "", "favorite" => intval(Main::getInstance()->getConfig()->getNested("default-settings.favorite-slot"))]);
    }

    /**
     * Summary of loadPlayers
     * @param Closure|null $callback
     * @return void
     */
    public function loadPlayers(?Closure $callback = null)
    {
        $this->database->executeSelect(self::LOAD, [], $callback);
    }

    /**
     * Summary of savePlayer
     * @param string $uuid
     * @param bool $enable
     * @param Blacklist $blacklist
     * @param int $favoriteSlot
     * @return void
     */
    public function savePlayer(string $uuid, bool $enable, Blacklist $blacklist, int $favoriteSlot)
    {
        $this->database->executeChange(self::UPDATE, ["uuid" => $uuid, "enabled" => $enable, "blacklist" => $blacklist->toString(), "favorite" => $favoriteSlot]);
    }

    /**
     * Summary of close
     * @return void
     */
    public function close()
    {
        /**
         * @var PlayerSetting $ps
         */
        foreach(Main::getInstance()->getPlayerManager()->getPlayerSettings() as $uuid => $ps) {
            $this->savePlayer($uuid, $ps->isEnable(), $ps->getBlacklist(), $ps->getFavoriteSlot());
        }
        $this->database->waitAll();
        $this->database->close();
    }

    public function reset()
    {
        $this->database->executeChange(self::RESET);
    }
}
