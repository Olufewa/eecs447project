<?php include_once 'includes/header.php'; ?>

<main class="content-container">
    <h2 class="page-title">Register</h2>

    <form action="register-process.php" method="post" class="registration-form">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        
        <button type="submit">Register</button>
    </form>
</main>

<?php include_once 'includes/footer.php'; ?>
