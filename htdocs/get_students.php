<?php
// データベースへ接続するために必要な情報
// ホストはDBコンテナ
$host = 'mysql';
// mysql接続用のユーザー名
$username = 'data_user';
//パスワード
$password = 'data';
//データベース名
$database = 'data_master';

//データベース処理開始,途中でエラーが発生でcatchへ
try {
    // PDO(PHP Data Objects)でMySQLに接続
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    //設定するとPDOExceptionが発生する
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // データの取得(コマンド実行)
    $stmt = $pdo->query("SELECT * FROM students");
    //連想配列で結果をすべて取得
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 取得したデータをセッションに保存
    session_start();
    $_SESSION['data'] = $results;

    // リダイレクト
    header("Location: display_students.php");
    exit();

} catch (PDOException $e) {
    // エラー処理
    echo "データベースエラー: " . $e->getMessage();
}
?>
