<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Просмотр заметки</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Дневник путешествий</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Переключить навигацию">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="create_post.php">Создать заметку</a></li>
                        <li class="nav-item"><a class="nav-link" href="my_posts.php">Мои заметки</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Выйти</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="register.php">Регистрация</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Войти</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php
        if (isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $stmt = $pdo->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = ?");
            $stmt->execute([$post_id]);
            $post = $stmt->fetch();

            if ($post):
        ?>
            <div class="post mt-5">
                <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                <p class="post-meta">Автор: <?php echo htmlspecialchars($post['username']); ?> | Дата: <?php echo date('d.m.Y H:i', strtotime($post['created_at'])); ?></p>
                <div class="post-content">
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                </div>
                <div class="rating mt-4">
                    <?php if ($post['travel_cost']): ?>
                        <p><strong>Стоимость путешествия:</strong> <?php echo htmlspecialchars($post['travel_cost']); ?> руб.</p>
                    <?php endif; ?>
                    <?php if ($post['transportation_rating']): ?>
                        <p><strong>Оценка передвижения:</strong> <?php echo htmlspecialchars($post['transportation_rating']); ?>/5</p>
                    <?php endif; ?>
                    <?php if ($post['safety_rating']): ?>
                        <p><strong>Оценка безопасности:</strong> <?php echo htmlspecialchars($post['safety_rating']); ?>/5</p>
                    <?php endif; ?>
                    <?php if ($post['population_density_rating']): ?>
                        <p><strong>Оценка населенности:</strong> <?php echo htmlspecialchars($post['population_density_rating']); ?>/5</p>
                    <?php endif; ?>
                    <?php if ($post['vegetation_rating']): ?>
                        <p><strong>Оценка растительности:</strong> <?php echo htmlspecialchars($post['vegetation_rating']); ?>/5</p>
                    <?php endif; ?>
                </div>
                <?php if ($post['places_to_visit']): ?>
                    <h3>Места для посещения:</h3>
                    <p><?php echo nl2br(htmlspecialchars($post['places_to_visit'])); ?></p>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
                    <div class="post-actions mt-4">
                        <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-secondary">Редактировать</a>
                        <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить эту заметку?');">Удалить</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php
            else:
                echo '<div class="alert alert-danger mt-5" role="alert">Запись не найдена</div>';
            endif;
        } else {
            echo '<div class="alert alert-danger mt-5" role="alert">Некорректный запрос</div>';
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
