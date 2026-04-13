<?php
session_start();      // Mengaktifkan session

// Header JSON untuk response AJAX
header('Content-Type: application/json');

// Pengecekan session login user 
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.']);
    exit;
} else {
    // Panggil file koneksi database
    require_once "../../config/database.php";

    // Pastikan data dikirim melalui method POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ambil data hasil submit dari form
        $id_dokumen = mysqli_real_escape_string($mysqli, $_POST['id_dokumen']);
        $nama_dokumen = mysqli_real_escape_string($mysqli, trim($_POST['nama_dokumen']));

        // Ambil data file hasil submit
        $nama_file = $_FILES['file_dokumen']['name'];
        $tmp_file = $_FILES['file_dokumen']['tmp_name'];

        // Jika data file tidak diunggah (Hanya ubah nama dokumen)
        if (empty($nama_file)) {
            // SQL statement untuk update nama dokumen saja
            $update = mysqli_query($mysqli, "UPDATE tbl_dokumen 
                                            SET nama_dokumen = '$nama_dokumen' 
                                            WHERE id = '$id_dokumen'")
                or die('Error: ' . mysqli_error($mysqli));

            if ($update) {
                echo json_encode(['status' => 'success', 'message' => 'Nama dokumen berhasil diperbarui.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data.']);
            }
        }
        // Jika user mengunggah file baru
        else {
            // Ambil ekstensi file
            $get_ext = explode(".", $nama_file);
            $extension = end($get_ext);

            // Enkripsi nama file agar unik seperti template Anda
            $nama_file_baru = sha1(md5(time() . $nama_file)) . '.' . $extension;

            // Tentukan direktori penyimpanan file (Sesuaikan dengan folder upload Anda)
            $path = "../../uploads/dokumen/" . $nama_file_baru;

            // Proses unggah file
            if (move_uploaded_file($tmp_file, $path)) {

                // OPTIONAL: Hapus file lama dari server agar tidak memenuhi disk
                $query_lama = mysqli_query($mysqli, "SELECT file_dokumen FROM tbl_dokumen WHERE id = '$id_dokumen'");
                $data_lama = mysqli_fetch_assoc($query_lama);
                if (!empty($data_lama['file_dokumen'])) {
                    $file_lama_path = "../../uploads/dokumen/" . $data_lama['file_dokumen'];
                    if (file_exists($file_lama_path)) {
                        unlink($file_lama_path); // Menghapus file fisik lama
                    }
                }

                // SQL statement untuk update nama dokumen dan file baru
                $update = mysqli_query($mysqli, "UPDATE tbl_dokumen 
                                                SET nama_dokumen = '$nama_dokumen', 
                                                    file_dokumen = '$nama_file_baru' 
                                                WHERE id = '$id_dokumen'")
                    or die('Error: ' . mysqli_error($mysqli));

                if ($update) {
                    echo json_encode(['status' => 'success', 'message' => 'Data dan file dokumen berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'File terupload, tapi gagal update database.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal mengunggah file ke server.']);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Metode akses tidak diizinkan.']);
    }
}
?>