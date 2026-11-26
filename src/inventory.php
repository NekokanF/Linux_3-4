
<?php

require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');

$userId = 1;

$stmt = $pdo->prepare("
    SELECT
        items.id,
        items.name,
        rarities.name AS rarity,
        user_items.quantity
    FROM user_items
    INNER JOIN items
        ON user_items.item_id = items.id
    INNER JOIN rarities
        ON items.rarity_id = rarities.id
    WHERE user_items.user_id = :user_id
    ORDER BY rarities.id DESC, items.id ASC
");

$stmt->execute([
    ':user_id' => $userId
]);

echo json_encode(
    $stmt->fetchAll(),
    JSON_UNESCAPED_UNICODE
);
