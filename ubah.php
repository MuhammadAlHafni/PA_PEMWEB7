<?php
require 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $koneksi->prepare("SELECT * FROM produk WHERE id_produk = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produk = $result->fetch_assoc();
    $stmt->close();
}

if (isset($_POST['ubah'])) {
    $nama_produk = $_POST["nama_produk"];
    $metodepembayaran_produk = $_POST["metodepembayaran_produk"];
    $jumlah_produk = $_POST["jumlah_produk"];
    $deskripsi_produk = $_POST["deskripsi_produk"];

    if ($_FILES["foto_produk"]["error"] == 0) {
        if (file_exists($produk['foto_produk'])) {
            unlink($produk['foto_produk']);
        }

        $foto_produk_name = $_FILES["foto_produk"]["name"];
        $temp_name = $_FILES["foto_produk"]['tmp_name'];
        $file_ext = pathinfo($foto_produk_name, PATHINFO_EXTENSION);

        $current_date = date('Y-m-d');

        $new_file_name = $current_date . "-" . basename($foto_produk_name, "." . $file_ext) . "." . $file_ext;
        $path = "../foto_produk1/" . $new_file_name;

        if (!move_uploaded_file($temp_name, $path)) {
            echo "<script>
                    alert('Gagal mengunggah gambar.');
                  </script>";
            return;
        }
    } else {
        $path = $produk['foto_produk'];
    }

    $stmt = $koneksi->prepare("UPDATE produk SET nama_produk = ?, metodepembayaran_produk = ?, jumlah_produk = ?, foto_produk = ?, deskripsi_produk = ? WHERE id_produk = ?");
    $stmt->bind_param("ssissi", $nama_produk, $metodepembayaran_produk, $jumlah_produk, $path, $deskripsi_produk, $id);
    
    if ($stmt->execute()) {
        echo "<script>
                alert('Data Berhasil Diubah');
                document.location.href = 'tampilan.php';
              </script>";
    } else {
        echo "<script>
                alert('Data Gagal Diubah');
              </script>";
    }

    $stmt->close();
}

?>


<!DOCTYPE html>
<head>
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
        }


        .sidebar {
            float: left;
            width: 20%;
            background-color: #333;
            border: none;
            padding-top: 10px;
            height: 100vh;
            padding-top: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo {
            width: 100%;
            text-align: center;
            padding-bottom: 20px;
            border: none;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            max-width: 80%;
        }

        .sidebar a {
            display: block;
            color: white;
            text-align: center;
            padding: 20px;
            width: 100%;
            text-decoration: none;
            border: none;
        }
        .sidebar a:last-child {
            border-bottom: none;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .sidebar a:hover {
            background-color: #444;
            border-radius: 5px;
            width: 60%;
            color: white;
        }

        form {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 50px;
        }

        form h2 {
            font-size: 1.8rem;
            color: var(--main-color);
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-top: 15px;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        form input[type="text"], form input[type="number"] {
            width: 100%;
            padding: 10px;
            background-color: #555;
            border: 1px solid #777;
            color: white;
            border-radius: 5px;
        }

        form button {
            display: block;
            width: 100%;
            padding: 10px 15px;
            margin-top: 20px;
            background-color: #555;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: 0.3s;
        }

        form button:hover {
            background-color: red;
        }

        form select, form input[type="file"] {
            width: 100%;
            padding: 10px;
            background-color: #555;
            border: 1px solid #777;
            color: white;
            border-radius: 5px;
            appearance: none;
            font-size: 1rem;
            cursor: pointer;
        }

        form input[type="file"]::-webkit-file-upload-button {
            background-color: orangered;
            border: none;
            border-radius: 5px;
            color: white;
            padding: 5px 10px;
            margin-top: 2px;
            cursor: pointer;
        }

        form input[type="file"]::-webkit-file-upload-button:hover {
            background-color: #d65d1a;
        }

    </style>
</head>
<div class="sidebar">
        <div class="logo">
            <img src="chocolate (4) (1).png" alt="Logo">
        </div>
        <a href="tampilan.php">Data Checkout</a>
        <a href="index.html?#menu">Lihat Menu</a>
    </div>

<form action="" method="post" enctype="multipart/form-data">
<label for="nama_produk">Nama Produk : </label>
    <input type="text" name="nama_produk">

    <label for="metodepembayaran_produk">Pilih Metode Pembayaran :</label>
    <select name="metodepembayaran_produk" >
        <option value="Dana">Dana</option>
        <option value="Gopay">Gopay</option>
        <option value="MBanking">MBanking</option>
    </select>

    <label for="jumlah_produk">Jumlah Produk :</label>
    <input type="number" name="jumlah_produk">

    <label for="foto_produk">Bukti Transaksi :</label>
    <input type="file" name="foto_produk">

    <label for="deskripsi_produk">Deskripsi (Data Alamat) :</label>
    <textarea name="deskripsi_produk" rows="10"></textarea>

    <button type="submit" name="ubah">Ubah</button>
</form>
    
</body>
</html>
