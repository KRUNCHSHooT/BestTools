-- #! sqlite
-- #{ besttools
-- #  { init
CREATE TABLE IF NOT EXISTS playerSetting(
    uuid TEXT NOT NULL,
    besttools_enabled BOOLEAN,
    blacklist TEXT,
    favorite_slot BIT,
    PRIMARY KEY(uuid)
);
-- #  }
-- #  { register
-- #    :uuid string
-- #    :enabled bool
-- #    :blacklist string
-- #    :favorite int
INSERT INTO playerSetting(
    uuid,
    besttools_enabled,
    blacklist,
    favorite_slot
) VALUES (
    :uuid,
    :enabled,
    :blacklist,
    :favorite
);
-- #  }
-- #  { loadPlayers
SELECT 
    uuid,
    besttools_enabled,
    blacklist,
    favorite_slot
FROM playerSetting;
-- #  }
-- #  { update
-- #    :uuid string
-- #    :enabled bool
-- #    :blacklist string
-- #    :favorite int
UPDATE playerSetting SET
    besttools_enabled=:enabled,
    blacklist=:blacklist,
    favorite_slot=:favorite
WHERE uuid=:uuid;
-- #  }
-- #  { reset
DELETE FROM playerSetting;
-- #  }
-- #}