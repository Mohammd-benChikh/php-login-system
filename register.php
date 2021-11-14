<?php include_once './includes/header.php' ?>
<?php
if (isset($_SESSION['auth']) && $_SESSION['auth']['state'] == true) {
    header('location: /');
}

$_SESSION['errors'] = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    require_once './includes/database.php';
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
    $errors = [];
    if (empty($username)) {
        $errors['username'][] = 'Username field is required';
    }

    if (!ctype_alnum( $username)) {
        $errors['username'][] = "Username must to be Only letters and numbers";
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username='$username'");
    $stmt->execute();
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $errors['username'][] = "The username has been already exists";
    }

    if (empty($email)) {
        $errors['email'][] = 'Email field is required';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'][] = "Invalid email format";
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email='$email'");
    $stmt->execute();
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $errors['email'][] = "The email has been already exists";
    }

    if (empty($password)) {
        $errors['password'][] = 'Password field is required';
    }

    if (strlen($password) < 8) {
        $errors['password'][] = 'Password should be at least 8 characters';
    }

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username,email,password) VALUES ('$username','$email','$password')";
        $conn->exec($sql);
        $_SESSION['auth'] = [
            'state' => true,
            'user' => [
                'id' => $conn->lastInsertId(),
                'username' => $username,
                'email' => $email
            ],
        ];
        header('location: /');
    }
}

function oldValue($value)
{
    if (isset($_POST[$value])) {
        echo $_POST[$value];
    } else {
        echo "";
    }
}

function hasError($error)
{
    if (isset($_SESSION['errors']) && isset($_SESSION['errors'][$error])) {
        return true;
    }
    return false;
}
?>

<div class="container">
    <div class="login-page">
        <div class="login-form">
            <div class="login-page-header">
                <h2>Register</h2>

            </div>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <div>
                    <label for="username">Username</label>
                    <input value="<?php oldValue("username") ?>" type="username" id="username" class="<?php if (hasError('username')) echo 'has-error'  ?>" name="username" placeholder="Username">
                    <?php if (hasError('username')) : ?>
                        <span class="error-message"><?php echo $errors['username'][0] ?></span>
                    <?php endif ?>
                </div>
                <div>
                    <label for="email">Email address</label>
                    <input value="<?php oldValue('email') ?>" type="email" id="email" name="email" class="<?php if (hasError('email')) echo 'has-error'  ?>" placeholder="Email address">
                    <?php if (hasError('email')) : ?>
                        <span class="error-message"><?php echo $errors['email'][0] ?></span>
                    <?php endif ?>
                </div>
                <div>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="<?php if (hasError('password')) echo 'has-error'  ?>" placeholder="Password">
                    <?php if (hasError('password')) : ?>
                        <span class="error-message"><?php echo $errors['password'][0] ?></span>
                    <?php endif ?>
                </div>
                <button name="register" type="submit">Register</button>
                <a href="/login.php">I already have account</a>
            </form>
        </div>

    </div>
</div>
<?php include_once './includes/footer.php' ?>