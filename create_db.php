<?php
try {
    $db = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("
        CREATE TABLE IF NOT EXISTS feed_items (
            entity_id INTEGER PRIMARY KEY,
            category_name TEXT,
            sku TEXT,
            name TEXT,
            shortdesc TEXT,
            price REAL,
            link TEXT,
            image TEXT,
            brand TEXT,
            rating INTEGER,
            caffeine_type TEXT,
            count INTEGER,
            flavored TEXT,
            seasonal TEXT,
            instock TEXT,
            facebook INTEGER,
            is_kcup INTEGER
        );
    ");
    echo "Database and table created successfully.";
} catch (PDOException $e) {
    echo "Error creating database: " . $e->getMessage();
}
?>
