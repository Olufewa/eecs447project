<?php include_once 'includes/header.php'; ?>

<main class="registration-container">
    <h2 class="registration-title">Add Admin</h2>
    <form action="add_admin_process.php" method="post" class="registration-form">
        <label for="username" class="registration-label">Username:</label><br>
        <input type="text" id="username" name="username" required class="registration-input"><br>
        <label for="email" class="registration-label">Email:</label><br>
        <input type="email" id="email" name="email" required class="registration-input"><br>
        <label for="password" class="registration-label">Password:</label><br>
        <input type="password" id="password" name="password" required class="registration-input"><br>
        <button type="submit" class="registration-button">Register</button>
    </form>
</main>

<?php include_once 'includes/footer.php'; ?>
