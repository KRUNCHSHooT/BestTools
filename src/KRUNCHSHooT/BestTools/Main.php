<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools;

use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\LegacyStringToItemParserException;
use pocketmine\item\StringToItemParser;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\utils\TextFormat;
use SQLite3;

use KRUNCHSHooT\BestTools\commands\BestToolsCommand;
use KRUNCHSHooT\BestTools\commands\BlacklistCommand;

class Main extends PluginBase {
    
    /** PlayerSetting[] $playerSettings */
    public $playerSettings = [];
    
    public function onEnable() : void {
        
        $db = new SQLite3($this->getDataFolder() . "besttools.db");
        $db->exec("CREATE TABLE IF NOT EXISTS besttools(uuid TEXT, besttools_use TINYINT(1), blacklist TEXT)");
        $db->close();
        
        $this->getServer()->getPluginManager()->registerEvents(new BestToolsListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new BestToolsCacheListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener($this), $this);
        
        $this->getServer()->getCommandMap()->registerAll("BestTools", [new BestToolsCommand($this), new BlacklistCommand($this)]);
    }
    
    public function onDisable() : void {
        $this->saveAllPlayerSettings();
    }
    
    public function getPlayerSetting(Player $player){
        if(isset($this->playerSettings[str_replace("-", "", $player->getUniqueId()->toString())])){
            return $this->playerSettings[str_replace("-", "", $player->getUniqueId()->toString())];
        }
        
        $db = new SQLite3($this->getDataFolder() . "besttools.db");
        $query = $db->query("SELECT * FROM besttools WHERE uuid = '" . SQLite3::escapeString(str_replace("-", "", $player->getUniqueId()->toString())) . "'")->fetchArray(SQLITE3_ASSOC);
        if(is_array($query)){
            $blacklist = explode(",", $query["blacklist"]);
            $set = new PlayerSetting((bool)$query["besttools_use"], $blacklist);
        } else {
            $db->exec("INSERT INTO besttools(uuid, besttools_use, blacklist) VALUES ('" . SQLite3::escapeString(str_replace("-", "", $player->getUniqueId()->toString())) . "', 1, '')");
            $set = new PlayerSetting(true);
        }
        $db->close();
        
        $this->playerSettings[str_replace("-", "", $player->getUniqueId()->toString())] = $set;
        return $set;
    }
    
    public function saveAllPlayerSettings(){
        foreach($this->playerSettings as $uuid => $ps){
            $ps->save($this->getDataFolder() . "besttools.db", $uuid);
        }
    }
}
