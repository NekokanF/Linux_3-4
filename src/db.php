// データベース接続部分
<?php
// db.phpで処理を行いその後gacha.phpにインクルード(require_once)で持ってくる
$host = 'mysql';
$dbname = 'gacha_db';
$user = 'gacha_user';
$password = 'gacha_pass';

try { 
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed');
}