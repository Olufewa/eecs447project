<?php
include_once 'includes/header.php';
?>

<main class="login-container">
    <h2 class="login-title">Login</h2>
    <form action="login-process.php" method="post" class="login-form">
        <label for="username" class="login-label">Username:</label><br>
        <input type="text" id="username" name="username" required class="login-input"><br>
        <label for="password" class="login-label">Password:</label><br>
        <input type="password" id="password" name="password" required class="login-input"><br>
        <button type="submit" class="login-button">Login</button>
    </form>
</main>

<?php
include_once 'includes/footer.php';
?>
