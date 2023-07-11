<?php

declare(strict_types=1);

namespace KRUNCHSHooT\BestTools\player;

use KRUNCHSHooT\BestTools\blacklist\Blacklist;
use pocketmine\block\Block;
use pocketmine\world\Position;

class PlayerSetting
{
    private Blacklist $blacklist;
    public int $btcache = 0;
    public ?Position $link = null;
    public bool $cmode = false;

    public function __construct(public bool $enable = false, $blarray = [], private int $favoriteSlot = 0)
    {
        $this->enable = $enable;
        $this->blacklist = new Blacklist($blarray);
    }

    public function getBlacklist(): Blacklist
    {
        return $this->blacklist;
    }

    public function isEnable(): bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable = true)
    {
        $this->enable = $enable;
    }

    public function getLinked(): ?Position
    {
        return $this->link;
    }

    public function setLinked(?Position $pos = null)
    {
        $this->link = $pos;
    }

    public function getFavoriteSlot(): int
    {
        return $this->favoriteSlot;
    }

    public function setFavoriteSlot(int $slot): bool
    {
        if($slot >= 0 && $slot <= 8) {
            $this->favoriteSlot = $slot;
            return true;
        }
        return false;
    }

    public function getFPStats(): string
    {
        if($this->link !== null) {
            return "§aLinked!, §eCoordinate: (" . $this->link->x . "," . $this->link->y . "," . $this->link->z . ")";
        }
        return "§cOFF";
    }

    public function unvalidate(): void
    {
        $this->btcache = 0;
    }

    public function validate(Block $block): void
    {
        $this->btcache = $block->getTypeId();
    }

    public function valid(Block $block): bool
    {
        if(empty($this->btcache)) {
            return false;
        }
        if($block->getTypeId() == $this->btcache) {
            return true;
        }
        return false;
    }
}
