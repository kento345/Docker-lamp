<?php
// データベースへ接続するために必要な情報
// ホストはDBコンテナ
$host = 'mysql';
// mysql接続用のユーザー
$username = 'data_user';
$password = 'data';
$database = 'data_master';

// --- Redis設定 ---
$redisHost = 'redis';
$redisPort = 6379;

echo "--- データベースからデータを取得し、Redisに保存します ---\n";

// データベースへ接続
try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "データベースに接続しました。\n";
} catch (PDOException $e) {
    die("データベース接続エラー: " . $e->getMessage() . "\n");
}

// データの取得
try {
    $stmt = $pdo->query("SELECT student_id, student_name, class_id FROM students");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "学生データ " . count($students) . " 件を取得しました。\n";
} catch (PDOException $e) {
    die("データ取得エラー: " . $e->getMessage() . "\n");
}

// Redisへ接続
$redis = new Redis();
try {
    $redis->connect($redisHost, $redisPort);
    echo "Redisに接続しました。\n";
} catch (RedisException $e) {
    die("Redis接続エラー: " . $e->getMessage() . "\n");
}

// データをRedisへ保存
$savedCount = 0;
foreach ($students as $student) {
    $redisKey = "student:" . $student['student_id'];

    $redis->hMSet($redisKey, [
        'student_name' => $student['student_name'],
        'class_id' => $student['class_id']
    ]);

    $redis->expire($redisKey, 60);

    $savedCount++;
    echo "  -> Redisにキー: {$redisKey} を保存しました。\n";
}

echo "合計 {$savedCount} 件のデータをRedisに保存しました。\n";

// 接続を閉じる
$pdo = null;
$redis->close();
echo "処理が完了しました。\n";

?>
