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
        die('Запись не найдена или у вас нет прав для ее редактирования.');
    }
} else {
    die('Некорректный запрос');
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $travel_cost = $_POST['travel_cost'] ?: null;
    $transportation_rating = $_POST['transportation_rating'] ?: null;
    $safety_rating = $_POST['safety_rating'] ?: null;
    $population_density_rating = $_POST['population_density_rating'] ?: null;
    $vegetation_rating = $_POST['vegetation_rating'] ?: null;
    $places_to_visit = trim($_POST['places_to_visit']);

    if (empty($title) || empty($content)) {
        $message = '<div class="alert alert-danger" role="alert">Пожалуйста, заполните все обязательные поля</div>';
    } else {
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, travel_cost = ?, transportation_rating = ?, safety_rating = ?, population_density_rating = ?, vegetation_rating = ?, places_to_visit = ? WHERE id = ?");
        if ($stmt->execute([
            $title,
            $content,
            $travel_cost,
            $transportation_rating,
            $safety_rating,
            $population_density_rating,
            $vegetation_rating,
            $places_to_visit,
            $post_id
        ])) {
            $message = '<div class="alert alert-success" role="alert">Запись успешно обновлена</div>';
            $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
            $stmt->execute([$post_id, $_SESSION['user_id']]);
            $post = $stmt->fetch();
        } else {
            $message = '<div class="alert alert-danger" role="alert">Ошибка при обновлении записи</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать заметку</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Дневник путешествий</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Переключить навигацию">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="my_posts.php">Мои заметки</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Выйти</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="mt-5">Редактировать заметку</h1>
        <?php echo $message; ?>
        <form method="post" action="">
            <div class="mb-3">
                <label class="form-label">Заголовок</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Содержание</label>
                <textarea name="content" class="form-control" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Стоимость путешествия (руб.)</label>
                <input type="number" step="0.01" name="travel_cost" class="form-control" value="<?php echo htmlspecialchars($post['travel_cost']); ?>">
            </div>
            <div class="mb-3">
                <label class="rating-label">Оценка передвижения (1-5)</label>
                <input type="number" name="transportation_rating" min="1" max="5" class="form-control rating-input" value="<?php echo htmlspecialchars($post['transportation_rating']); ?>">
            </div>
            <div class="mb-3">
                <label class="rating-label">Оценка безопасности (1-5)</label>
                <input type="number" name="safety_rating" min="1" max="5" class="form-control rating-input" value="<?php echo htmlspecialchars($post['safety_rating']); ?>">
            </div>
            <div class="mb-3">
                <label class="rating-label">Оценка населенности (1-5)</label>
                <input type="number" name="population_density_rating" min="1" max="5" class="form-control rating-input" value="<?php echo htmlspecialchars($post['population_density_rating']); ?>">
            </div>
            <div class="mb-3">
                <label class="rating-label">Оценка растительности (1-5)</label>
                <input type="number" name="vegetation_rating" min="1" max="5" class="form-control rating-input" value="<?php echo htmlspecialchars($post['vegetation_rating']); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Места для посещения</label>
                <textarea name="places_to_visit" class="form-control" rows="3"><?php echo htmlspecialchars($post['places_to_visit']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Обновить</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
