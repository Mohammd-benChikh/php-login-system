<?php include_once './includes/header.php' ?>
<?php
if (isset($_SESSION['auth']) && $_SESSION['auth']['state'] == true) {
    header('location: /');
}

$_SESSION['errors'] = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    require_once './includes/database.php';
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
    $errors = [];


    if (empty($email)) {
        $errors['email'][] = 'Email field is required';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'][] = "Invalid email format";
    }

    if (empty($password)) {
        $errors['password'][] = 'Password field is required';
    }

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email='$email'");
        $stmt->execute();
        $user = $stmt->fetch();
        if ($user && password_verify($password,$user['password'])) {
            $_SESSION['auth'] = [
                'state' => true,
                'user' =>$user,
            ];
            header('location: /');
        } else {
            $_SESSION['errors']['login'] = "your credentials don't match in our records";
        }
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
                <h2>Log in</h2>

            </div>

            <form action="/login.php" method="post">
            <?php if(hasError('login')):?>
                <div class="error-alert">
                    <p><?php echo $_SESSION['errors']['login']  ?></p>
                </div>
            <?php endif?>
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
                <button name="login" type="submit">Log in</button>
            </form>
        </div>

    </div>
</div>
<?php include_once './includes/footer.php' ?>