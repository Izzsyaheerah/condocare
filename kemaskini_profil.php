<?php
session_start();
include 'database.php';

// Semak jika pengguna telah log masuk
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Dapatkan maklumat pengguna
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM tbl_pengguna WHERE fld_id_pengguna = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Pengguna tidak dijumpai!";
    exit();
}

// Proses kemaskini bila submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no_tel = $_POST['fld_no_tel'];

    // Upload gambar jika ada fail baru
    if (!empty($_FILES['fld_gambar']['name'])) {
        $gambarName = uniqid() . '_' . $_FILES['fld_gambar']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($gambarName);

        if (move_uploaded_file($_FILES['fld_gambar']['tmp_name'], $target_file)) {
            $gambar_baru = $gambarName;

            // Padam gambar lama kalau ada
            if (!empty($user['fld_gambar'])) {
                $gambar_lama = "uploads/" . $user['fld_gambar'];
                if (file_exists($gambar_lama)) {
                    unlink($gambar_lama);
                }
            }
        }
    } else {
        $gambar_baru = $user['fld_gambar']; // Kekalkan gambar lama
    }

    // Update data
    $stmt = $conn->prepare("UPDATE tbl_pengguna SET fld_no_tel = ?, fld_gambar = ? WHERE fld_id_pengguna = ?");
    $stmt->execute([$no_tel, $gambar_baru, $user_id]);

    header("Location: kemaskini_profil.php");
    exit();
}

// Proses padam gambar
if (isset($_GET['padam_gambar'])) {
    if (!empty($user['fld_gambar'])) {
        $gambar_lama = "uploads/" . $user['fld_gambar'];
        if (file_exists($gambar_lama)) {
            unlink($gambar_lama);
        }
    }
    $stmt = $conn->prepare("UPDATE tbl_pengguna SET fld_gambar = '' WHERE fld_id_pengguna = ?");
    $stmt->execute([$user_id]);
    header("Location: kemaskini_profil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: rgb(222, 218, 200);
        }

        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .btn-custom {
            background: #D27D2C;
            color: #fff;
            font-weight: 600;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-custom:hover {
            background: #A65F20;
        }

        .form-label {
            font-weight: bold;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 3px solid #D27D2C;
        }

    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">
    <div class="container">
        <h3 class="mb-4 text-center">Kemaskini Profil</h3>

        <div class="text-center">
            <?php if (!empty($user['fld_gambar'])): ?>
    <img src="uploads/<?= $user['fld_gambar']; ?>" alt="Profile Picture" class="profile-pic">
<?php else: ?>
    <img src="uploads/default.jpg" alt="Profile Picture" class="profile-pic">
<?php endif; ?>

            <div class="mt-2">
                <a href="?padam_gambar" class="btn btn-danger btn-sm">Padam Gambar</a>
            </div>
        </div>

        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nama:</label>
                <input type="text" class="form-control" value="<?= $user['fld_nama']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">No Unit:</label>
                <input type="text" class="form-control" value="<?= $user['fld_no_unit']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Emel:</label>
                <input type="email" class="form-control" value="<?= $user['fld_emel']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">No Telefon:</label>
                <input type="text" class="form-control" name="fld_no_tel" value="<?= $user['fld_no_tel']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kemaskini Gambar:</label>
                <input type="file" class="form-control" name="fld_gambar" accept="image/*">
            </div>

            <button type="submit" class="btn-custom">KEMASKINI</button>
        </form>

    </div>
</div>

</body>
</html>