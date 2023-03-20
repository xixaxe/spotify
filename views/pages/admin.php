<?php
    if(empty($_SESSION['user']) OR $_SESSION['user']['role'] === '1') {
        header('Location: ?page=login');
        exit;
    }

    if(!empty($_POST)) {

        if(!empty($_FILES['photo']['tmp_name'])) {
            if(!is_dir('./uploads')) {
                mkdir('./uploads');
            }
    
            $filename = explode('.', $_FILES['photo']['name']);
            $filename = time() . '.' . $filename[count($filename) - 1];
    
            $imageTypes = ['image/apng', 'image/avif', 'image/gif', 'image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'];
    
            if (!in_array($_FILES['photo']['type'], $imageTypes)) {
                $params = [
                    'page' => 'admin',
                    'message' => 'Neteisingas failo formatas',
                    'status' => 'danger'
                ];
    
                header('Location: ?' . http_build_query($params));
                exit;
            }
    
            move_uploaded_file($_FILES['photo']['tmp_name'], './uploads/' . $filename);
    
            $_POST['photo'] = $filename;
            // header('Location: ?page=admin');
            // exit;
        }
        //print_r($_POST);

        $query = vsprintf(
            "INSERT INTO songs (name, author, album, year, link, photo) VALUES('%s', '%s', '%s', '%s', '%s', '%s')",
            $_POST
        );
        $db->query($query);

        header('Location: ?page=admin');
    exit;
    }

    $songs = $db->query("SELECT * FROM songs");
    $songs = $songs->fetch_all(MYSQLI_ASSOC);
?>


<div>
    <a href="?page=logout" class="btn btn-outline-danger float-end">Logout</a>
</div>




<h1>Admin</h1>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cover</th>
            <th>Song Name</th>
            <th>Author</th>
            <th>Album</th>
            <th>Year</th>
            <th>Link</th>
            <th>Date added</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($songs as $song): ?>
            <tr>
                <td><?= $song['id'] ?></td>
                <td><img src="./uploads/<?= $song['photo'] ?>" alt="cover" style="width: 50px;"></td>
                <td><?= $song['name'] ?></td>
                <td><?= $song['author'] ?></td>
                <td><?= $song['album'] ?></td>
                <td><?= $song['year'] ?></td>
                <td><?= $song['link'] ?></td>
                <td><?= $song['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>    
    </tbody>
</table>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Song Name:</label>
        <input type="text" name="name" class="form-control" />
    </div>
    <div class="mb-3">
        <label>Author:</label>
        <input type="text" name="author" class="form-control" />
    </div>
    <div class="mb-3">
        <label>Album:</label>
        <input type="text" name="album" class="form-control" />
    </div>
    <div class="mb-3">
        <label>Year:</label>
        <input type="text" name="year" class="form-control" />
    </div>
    <div class="mb-3">
        <label>Youtube Link:</label>
        <input type="text" name="link" class="form-control" />
    </div>
    <div class="mb-3">
        <label>Song cover:</label>
        <input type="file" name="photo" class="form-control" />
    </div>
    <button class="btn btn-primary">Add</button>
</form>

