<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="David Harden">
    <meta name="description" content="Log In">
    <title>Log In</title>
    <link rel="stylesheet" href="account.css">
</head>
<body>
    <main>
        <section class="create-account">
            <div class="container">
                <h1>Log In</h1>
                <p>Welcome back! Please log in to continue.</p>

                <!-- Error message display -->
                <?php if (isset($_GET['error'])): ?>
                    <p style="color: red; font-weight: bold;">
                        <?php
                        if ($_GET['error'] === 'invalid') {
                            echo "Invalid username or password. Please try again.";
                        } elseif ($_GET['error'] === 'database') {
                            echo "Database connection failed. Please try again later.";
                        }
                        ?>
                    </p>
                <?php endif; ?>

                <form action="login-back.php" method="post" class="account-form">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                
                    <button type="submit" class="btn">Log In</button>
                </form>
                <p>Don't have an account? <a href="create-account.html">Sign up here</a>.</p>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 David Harden. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
