<?php
session_start();
include_once 'database.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format emel tidak sah. Sila masukkan emel yang betul!";
    } else {
        try {
            // Query to find the user by email
            $stmt = $conn->prepare("SELECT * FROM tbl_pengguna WHERE fld_emel = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Jika kata laluan masih "Password123", paksa pengguna reset password
                if (trim($user['fld_kata_laluan']) === "Password123") {
                    echo "<script>
                            alert('Sila tukar kata laluan anda sebelum log masuk kali pertama!');
                            window.location.href='lupa_katalaluan.php?email=$email';
                          </script>";
                    exit();
                }

                // Jika kata laluan sepadan
                if (trim($password) === trim($user['fld_kata_laluan'])) {
                    $_SESSION['user_id'] = $user['fld_id_pengguna'];
                    $_SESSION['user_name'] = $user['fld_nama'];
                    $_SESSION['user_level'] = $user['fld_userlevel'];
                
                    if ($user['fld_userlevel'] === 'Pengurusan') {
                        header("Location: dashboard_admin.php");
                    } else {
                        header("Location: dashboard_pengguna.php");
                    }
                    exit();
                
                } else {
                    $error = "Emel atau kata laluan tidak sah. Sila cuba lagi!";
                }
            } else {
                $error = "Emel tidak dijumpai!";
            }
        } catch (PDOException $e) {
            $error = "Ralat: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Log Masuk - CondoCare</title>
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
        display: flex;
        align-items: center;
    }
    .header-bar img {
        height: 40px;
        margin-right: 15px;
    }
    .login-container {
        max-width: 1100px;
        margin: auto;
        margin-top: 80px;
        padding: 60px;
        background: #FFFFFF;
        border-radius: 15px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
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
    .register-link, .forgot-password-link {
        color: #FF6600;
        text-decoration: none;
        cursor: pointer;
    }
    .register-link:hover, .forgot-password-link:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
    <div class="header-bar">
        <img src="logo.jpg" alt="Logo">CONDO CARE
        
    </div>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="login-container">
            <h4 class="text-center fw-bold">Log Masuk</h4>
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger"> <?php echo $error; ?> </div>
            <?php } ?>
            <form method="post">
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Emel</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Kata Laluan</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="form-group form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePasswordVisibility(this)">
                    <label class="form-check-label" for="showPassword">Show Password</label>
                </div>
                <div class="text-center mb-3">
    <a href="lupa_katalaluan.php" class="forgot-password-link">Lupa Kata Laluan?</a>
</div>
                <div class="text-center">
                    <button type="submit" name="login" class="btn btn-custom w-100">Log Masuk</button>
                </div>
            </form>
        </div>
    </div>

 

    <script>
        function togglePasswordVisibility(checkbox) {
            var passwordField = document.getElementById("password");
            passwordField.type = checkbox.checked ? "text" : "password";
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>