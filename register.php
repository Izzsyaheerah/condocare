<?php
session_start();
include_once 'database.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle User Registration
if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $no_unit = $_POST['no_unit'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Encrypt password

    try {
        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT * FROM tbl_pengguna WHERE fld_emel = :email");
        $check_stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            $error = "Emel sudah didaftarkan. Sila gunakan emel lain.";
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO tbl_pengguna (fld_id_pengguna, fld_nama, fld_no_unit, fld_emel, fld_kata_laluan, fld_userlevel) VALUES (UUID(), :nama, :no_unit, :email, :password, 'Pengguna')");
            $stmt->bindParam(':nama', $nama, PDO::PARAM_STR);
            $stmt->bindParam(':no_unit', $no_unit, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();

            // Redirect to login page after registration
            header("Location: login.php?success=1");
            exit();
        }
    } catch (PDOException $e) {
        $error = "Ralat : No Unit Tidak Sah";
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Pengguna - CondoCare</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        background-color: #073B3A;
        font-family: 'Poppins', sans-serif;
    }
    .header-bar {
        background-color: #D27D2C;
        padding: 15px;
        color: white;
        font-size: 20px;
        font-weight: bold;
        text-align: left;
    }
    .register-container {
        max-width: 400px;
        margin: auto;
        margin-top: 100px;
        padding: 20px;
        background: #FFFFFF;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .btn-custom {
        background: #1D1D1D;
        color: #fff;
        font-weight: bold;
    }
    .btn-custom:hover {
        background: #000;
    }
    .form-control {
        border: none;
        border-bottom: 2px solid #000;
        border-radius: 0;
    }
    .form-control:focus {
        box-shadow: none;
        border-color: #FF6600;
    }
    .login-link {
        color: #FF6600;
        text-decoration: none;
    }
    .login-link:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
    <div class="header-bar">CONDO CARE</div>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="register-container">
            <h4 class="text-center fw-bold">Daftar Pengguna</h4>
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger"> <?php echo $error; ?> </div>
            <?php } ?>
            <form method="post">
                <div class="mb-3">
                    <label for="nama" class="form-label fw-bold">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
    <label for="no_unit" class="form-label fw-bold">No Unit</label>
    <input type="text" id="no_unit" name="no_unit" class="form-control" placeholder="A-##-##" required>
</div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Emel</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Kata Laluan</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="text-center">
                    <button type="submit" name="register" class="btn btn-custom w-100">Daftar Masuk</button>
                </div>
                <div class="text-center mt-3">
                    <p>Sudah mempunyai akaun? <a href="login.php" class="login-link">Log Masuk</a> sekarang!</p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
