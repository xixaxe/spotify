<?php
    if(!empty($_POST)) {
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $user = $db->query("SELECT id, role FROM users WHERE email = '{$email}' AND password = '{$password}'");
        $params = [
            'page' => 'login',
            'message' => 'Toks vartotojas nerastas',
            'status' => 'danger'
        ];

        if($user->num_rows === 0) {
            header('Location: ?' . http_build_query($params));
            exit;
        }

        $user = $user->fetch_row();

        $_SESSION['user']['id'] = $user[0];
        $_SESSION['user']['role'] = $user[1];

        header('Location: index.php');
        exit;
    }
?>

<h1 class="name">WELCOME TO SPOTIFY<h1>
<div class="login-form">
<form method="POST">
    <div class="mb-3">
        <label>Email address</label>
        <input type="email" name="email" placeholder="test@gmail.com" class="form-control" required />
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required />
    </div>
    <button class="btn btn-primary">Login</button>
    <p class="text-center"><a href="http://localhost/pirmas/1/Full-stack/spotify/index.php/?page=register">Create Account</a></p>
</form>
<link rel="stylesheet" href="css/style.css">
