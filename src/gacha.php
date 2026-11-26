// ガチャの抽選部分
<?php

require_once 'db_php';

header('Content-Type: application/json; charset=utf-8');

// 単発か10連かを受け取る
$count = filter_input(INPUT_POST, 'count', FILTER_VALIDATE_INT);

if (!inarray($count,[1,10],true)) {
    http_response_code(400);
    echo json_encode(['error' => ' Invalid count']);
    exit;
}

// 今回は動作確認用にユーザーIDを1に固定
$userId = 1;

try {
    $pdo->beginTransaction();

    // レア度と確率を取得
    $rarityStmt = $pdo->query(
        "SELECT id, name, probability FROM rarities"
    );

    $rarities = $rarityStmt->fetchAll();

    $totalProbability = array_sum(
        array_column($rarities,'probability')
    );

    if ($totalProbability !== 10000) {
        throw new Exception('Probability total must be 10000');
    }

    // アイテムを取得
    $itemStmt = $pdo->query(
        "SELECT id, name, rarity_id FROM items"
    );

    $items = $itemStmt->fetchAll();

    $itemsByRarity = [];

    foreach ($items as $item) {
        $itemsByRarity[$item['rarity_id']][] = $item;
    }

    foreach ($rarities as $rarity) {
        if (empty($itemsByRarity[$rarity['id']])) {
            throw new Exception('Rarity has no items');
        }
    }

    // 抽選結果
    $results = [];

    for ($i = 0; $i < $count; $i++) {

        // レア度抽選
        $random = random_int(1,$totalProbability);

        $current = 0;
        $selectRarity = null;

        foreach ($rarities as $rarity) {
            $current += (int)$rarity['probability'];

            if ($random <= $current) { 
                $selectRarity = $rarity;
                break;
            }
        }

        // 同じレア度のアイテムからランダム選択
        $ratgetItems = $itemsMyRarity[$selectedRarity['id']];

        $itemIndex = random_int(0,count($targetItems) - 1);

        $selectedItem = $targetItems[$itemIndex];

        // 所持アイテムに追加
        $insertStmt = $pdo->prepare("
            INSERT INTO user_items
                (user_id, item_id, quantity)
            VALUES 
                (:user_id, :item_id, 1)
            ON DUPLICATE KEY UPDATE
                quantity = quantity + 1
        ");

        $insertStmt->execute([
            ':user_id' => $iserId,
            ':item_id' => $selectedItem['id']
        ]);

        $results[] = [
            'item_id' => $selectedItem['id'],
            'item_name' => $selectedItem['name'],
            'rarity' => $selectedRarity['name']
        ];
    }

    $pdo->commit();

    echo json_encode([
        'results' => $results
    ],JSON_UNESCAPED_UNICODE);

    } catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);

    echo json_encode([
        'error' => 'Gacha failed'
    ], JSON_UNESCAPED_UNICODE);
}