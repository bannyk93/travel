<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Дневник путешествий</title>
    <!-- Bootstrap CSS -->
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
        $stmt = $pdo->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
        while ($post = $stmt->fetch()):
        ?>
            <div class="post">
                <h2 class="post-title"><a href="post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                <p class="post-meta">Автор: <?php echo htmlspecialchars($post['username']); ?> | Дата: <?php echo date('d.m.Y H:i', strtotime($post['created_at'])); ?></p>
                <p><?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 200))); ?>...</p>
                <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">Читать далее</a>
            </div>
            <hr>
        <?php endwhile; ?>
    </div>

    <!-- Футер -->
    <footer class="footer">
        <div class="container text-center">
            &copy; <?php echo date('Y'); ?> Дневник путешествий
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
