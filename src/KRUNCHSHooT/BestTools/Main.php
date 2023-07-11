<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools;

use CortexPE\Commando\PacketHooker;
use KRUNCHSHooT\BestTools\besttols\BestToolsCacheListener;
use KRUNCHSHooT\BestTools\besttols\BestToolsListener;
use KRUNCHSHooT\BestTools\blacklist\GlobalBlacklist;
use KRUNCHSHooT\BestTools\commands\BestToolsCommand;
use KRUNCHSHooT\BestTools\commands\FastPickCommand;
use KRUNCHSHooT\BestTools\database\Database;
use KRUNCHSHooT\BestTools\player\PlayerListener;
use KRUNCHSHooT\BestTools\player\PlayerManager;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{
    private int $maxdistance;
    private Database $database;
    private PlayerManager $playerManager;
    private GlobalBlacklist $globalBlacklist;

    private static self $instance;

    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        self::$instance = $this;
        if(!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }

        $this->maxdistance = (int) $this->getConfig()->get("max-distance");

        $this->database = new Database($this);
        $this->playerManager = new PlayerManager();
        $this->globalBlacklist = new GlobalBlacklist($this->getConfig()->get("global-blacklist"));


        $this->getServer()->getPluginManager()->registerEvents(new BestToolsListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new BestToolsCacheListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);

        $this->getServer()->getCommandMap()->register("BestTools", new BestToolsCommand($this, "besttools", "BestTools Command", ["bt"]));
        if(boolval($this->getConfig()->get("fastpick-enabled"))) {
            $this->getServer()->getCommandMap()->register("BestTools", new FastPickCommand($this, "fastpick", 'to turn BestTools Fast Pickup link and unlink.', ["fpu"]));
        }
    }

    public function onDisable(): void
    {
        if(isset($this->database)) {
            $this->database->close();
        }
    }

    public function reload()
    {
        $this->reloadConfig();
        if(isset($this->database)) {
            $this->database->close();
        }
        $this->database = new Database($this);
        $this->playerManager = new PlayerManager();
        $this->globalBlacklist = new GlobalBlacklist($this->getConfig()->get("global-blacklist"));
    }

    public function reset()
    {
        $this->database->reset();
        $this->playerManager = new PlayerManager();
        foreach($this->getServer()->getOnlinePlayers() as $player){
            $this->playerManager->createPlayerSetting($player);
        }
    }

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        return self::$instance;
    }

    /**
     * @return Database
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * @return int
     */
    public function getMaxdistance(): int
    {
        return $this->maxdistance;
    }

    public function getPlayerManager(): PlayerManager
    {
        return $this->playerManager;
    }

    public function getGlobalBlacklist(): GlobalBlacklist
    {
        return $this->globalBlacklist;
    }
}
