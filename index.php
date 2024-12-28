<?php
// Koneksi Database
$server = "localhost";
$user = "root";
$password = "";
$database = "crud_case3";

// Buat Koneksi
$koneksi = mysqli_connect($server, $user, $password, $database);

// Klik Tombol Simpan 
if (isset($_POST['bsimpan'])) {
    // Check if any of the form fields are empty
    if (empty($_POST['kBarang']) || empty($_POST['nBarang']) || empty($_POST['aBarang']) || empty($_POST['jumlah']) || empty($_POST['tDiterima'])) {
        echo "<script>
            alert('Semua field harus diisi!');
            document.location='index.php';
            </script>";
    } else {
        // Cek apakah halaman adalah edit atau baru
        if (isset($_POST['edit_id'])) {
            // Jika edit_id ada, berarti kita sedang mengedit
            $edit = mysqli_query($koneksi, "UPDATE tbarang SET
                                                kode = '$_POST[kBarang]',
                                                nama = '$_POST[nBarang]',
                                                asal = '$_POST[aBarang]',
                                                jumlah = '$_POST[jumlah]',
                                                tanggal_diterima = '$_POST[tDiterima]'
                                            WHERE id_barang = '$_POST[edit_id]'");

            // Jika edit data berhasil
            if ($edit) {
                echo "<script>
                alert('Edit Data Berhasil!');
                document.location='index.php';
                </script>";
            } else {
                echo "<script>
                alert('Edit Data Gagal!');
                document.location='index.php';
                </script>";
            }
        } else {
            // Data baru akan disimpan
            $simpan = mysqli_query($koneksi, "INSERT INTO tbarang (kode, nama, asal, jumlah, tanggal_diterima)
                                               VALUES ('$_POST[kBarang]', '$_POST[nBarang]', '$_POST[aBarang]', '$_POST[jumlah]', '$_POST[tDiterima]')");

            // Jika simpan data berhasil
            if ($simpan) {
                echo "<script>
                alert('Simpan Data Berhasil!');
                document.location='index.php';
                </script>";
            } else {
                echo "<script>
                alert('Simpan Data Gagal!');
                document.location='index.php';
                </script>";
            }
        }
    }
}

// Deklarasi variabel edit
$vkode = "";
$vnama = "";
$vasal = "";
$vjumlah = "";
$vtanggal_diterima = "";
$edit_id = "";

// Klik tombol edit & delete
if (isset($_GET['hal'])) {
    // Edit
    if ($_GET['hal'] == "edit") {
        // Tampilkan data yang akan di edit
        $tampil = mysqli_query($koneksi, "SELECT * FROM tbarang WHERE id_barang = '$_GET[id]' ");
        $data = mysqli_fetch_array($tampil);
        if ($data) {
            // Jika data ada, maka ditampung ke variabel
            $vkode = $data['kode'];
            $vnama = $data['nama'];
            $vasal = $data['asal'];
            $vjumlah = $data['jumlah'];
            $vtanggal_diterima = $data['tanggal_diterima'];
            $edit_id = $data['id_barang']; // Menyimpan id untuk editing
        }
    }

    // Hapus
    if ($_GET['hal'] == "delete") {
        // Hapus data berdasarkan ID
        $delete = mysqli_query($koneksi, "DELETE FROM tbarang WHERE id_barang = '$_GET[id]'");
        if ($delete) {
            echo "<script>
            alert('Data Berhasil Dihapus!');
            document.location='index.php';
            </script>";
        } else {
            echo "<script>
            alert('Data Gagal Dihapus!');
            document.location='index.php';
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Website</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h2>Data Inventory</h2>
        <h2>Kantor AkamKiko</h2>
    </header>

    <!-- Main Content -->
    <!-- Input Form -->
    <section class="form-container">
        <div class="form-header">
            <p>Form Input Data Barang</p>
        </div>

        <div class="form-input">
            <form action="index.php" method="post">
                <div class="form-content">
                    <label for="kBarang">Kode Barang</label><br>
                    <input type="text" value="<?= $vkode ?>" id="kBarang" name="kBarang"><br>
                    <label for="nBarang">Nama Barang</label><br>
                    <input type="text" value="<?= $vnama ?>" id="nBarang" name="nBarang" placeholder="Nama Barang"><br>
                    <label for="aBarang">Asal Barang</label><br>
                    <div class="select-container">
                        <select name="aBarang" id="aBarang">
                            <option value="<?= $vasal ?>"> >-Pilih-< </option>
                            <option value="Pembelian">Pembelian</option>
                            <option value="Peminjaman">Peminjaman</option>
                        </select>
                    </div>
                    <label for="jumlah">Jumlah</label>
                    <input type="number" value="<?= $vjumlah ?>" id="jumlah" name="jumlah" placeholder="Jumlah Barang">
                    <label for="tDiterima">Tanggal Diterima</label>
                    <input type="date" id="tDiterima" name="tDiterima" value="<?= $vtanggal_diterima ?>">

                    <!-- Hidden input to track edit -->
                    <?php if ($edit_id) { ?>
                        <input type="hidden" name="edit_id" value="<?= $edit_id ?>">
                    <?php } ?>
                </div>
                <hr>

                <div class="input-button-container">
                    <input class="input-button1" type="submit" name="bsimpan" value="Simpan">
                    <input class="input-button2" type="reset" name="breset" value="Reset">
                </div>
            </form>
        </div>
        <div class="form-footer"></div>
    </section>
    <br>
    <!-- Data Input -->
    <section class="dataInput">
        <div class="dataInputHeader">
            <p>Data Barang</p>
        </div>

        <div class="dataInputContent">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Asal Barang</th>
                        <th>Jumlah</th>
                        <th>Tanggal Diterima</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    // Menampilkan Data
                    $no = 1;
                    $tampil = mysqli_query($koneksi, "SELECT * FROM tbarang order by id_barang asc");
                    while ($data = mysqli_fetch_array($tampil)) {
                    ?>

                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['kode'] ?></td>
                            <td><?= $data['nama'] ?></td>
                            <td><?= $data['asal'] ?></td>
                            <td><?= $data['jumlah'] ?></td>
                            <td><?= $data['tanggal_diterima'] ?></td>
                            <td>
                                <a href="index.php?hal=edit&id=<?= $data['id_barang'] ?>"><button class="edit-button">Edit</button></a>
                                <a href="index.php?hal=delete&id=<?= $data['id_barang'] ?>"><button class="delete-button">Delete</button></a>
                            </td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

    <br><br><br>
</body>

</html>
