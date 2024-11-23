<?php
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$post_id, $_SESSION['user_id']]);
    $post = $stmt->fetch();

    if (!$post) {
        die('Запись не найдена или у вас нет прав для ее удаления.');
    } else {
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        if ($stmt->execute([$post_id])) {
            header('Location: my_posts.php');
            exit();
        } else {
            die('Ошибка при удалении записи.');
        }
    }
} else {
    die('Некорректный запрос');
}
?>
