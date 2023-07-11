<?php

namespace KRUNCHSHooT\BestTools\utils;

class Utils {

    public static function filterUUID(string $uuid) : string {
        return str_replace("-", "", $uuid);
    }
}