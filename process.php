<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];

        $allowedTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($fileType, $allowedTypes)) {
            echo "<div class='alert alert-danger flex items-center space-x-2 p-4 mb-4 text-sm text-red-800 bg-red-100 rounded-lg' role='alert'>
                    <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v6m0 0v6m0-6H6m6 0h6'></path>
                    </svg>
                    <span><strong>Error!</strong> Hanya file PDF dan Word (.docx) yang diperbolehkan.</span>
                  </div>";
            exit;
        }
        $action = $_POST['action'];

        $key = $_POST['key'];

        // Cek panjang kunci (harus 16 byte untuk AES-128)
        if (strlen($key) != 16) {
            echo "<div class='alert alert-danger flex items-center space-x-2 p-4 mb-4 text-sm text-red-800 bg-red-100 rounded-lg' role='alert'>

                    <span><strong>Error!</strong> Kunci harus 16 karakter untuk AES-128.</span>
                  </div>";
            exit;
        }

        // Baca isi file
        $fileData = file_get_contents($fileTmpPath);

        if ($action == 'encrypt') {
            // Proses enkripsi
            $iv = openssl_random_pseudo_bytes(16); // IV acak 16 byte

            // Encrypt data
            $encryptedData = openssl_encrypt($fileData, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);

            // Simpan hash kunci yang digunakan untuk enkripsi (kunci harus sama untuk dekripsi)
            $keyHash = hash('sha256', $key, true);

            // Gabungkan IV, data terenkripsi, dan hash kunci
            $finalData = $iv . $encryptedData . $keyHash;

            // Tentukan nama file terenkripsi
            $encryptedFileName = 'uploads/' . pathinfo($fileName, PATHINFO_FILENAME) . '_encrypted.' . pathinfo($fileName, PATHINFO_EXTENSION);

            // Simpan file terenkripsi
            file_put_contents($encryptedFileName, $finalData);

            echo "<div class='alert alert-success flex items-center space-x-2 p-4 mb-4 text-sm text-green-800 bg-green-100 rounded-lg' role='alert'>
                    <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path>
                    </svg>
                    <span><strong>Sukses!</strong> File berhasil dienkripsi dan disimpan sebagai <a href='" . $encryptedFileName . "' class='text-blue-600 underline'>Download File Enkripsi</a>.</span>
                  </div>";
        } elseif ($action == 'decrypt') {
            // Proses dekripsi

            // Ambil IV dari awal file
            $iv = substr($fileData, 0, 16);

            // Ambil data terenkripsi (setelah IV)
            $encryptedData = substr($fileData, 16, -32);  // Mengurangi 32 byte terakhir untuk hash kunci

            // Ambil hash kunci yang disimpan pada file terenkripsi
            $storedKeyHash = substr($fileData, -32);

            // Cek apakah kunci yang dimasukkan sesuai dengan kunci yang digunakan saat enkripsi
            if (hash('sha256', $key, true) !== $storedKeyHash) {
                echo "<div class='alert alert-danger flex items-center space-x-2 p-4 mb-4 text-sm text-red-800 bg-red-100 rounded-lg shadow-md' role='alert'>
            <span><strong>Error!</strong> Kunci yang dimasukkan tidak sesuai dengan kunci yang digunakan untuk mengenkripsi file.</span>
          </div>";
                exit;
            }

            // Dekripsi data file dengan AES-128-CBC
            $decryptedData = openssl_decrypt($encryptedData, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);

            // Tentukan nama file hasil dekripsi dan simpan di folder 'downloads'
            $decryptedFileName = 'downloads/' . pathinfo($fileName, PATHINFO_FILENAME) . '_decrypted.' . pathinfo($fileName, PATHINFO_EXTENSION);

            // Cek apakah folder 'downloads' ada, jika tidak buat
            if (!is_dir('downloads')) {
                mkdir('downloads', 0777, true); // Membuat folder dengan izin penuh
            }

            // Simpan hasil dekripsi sebagai file di folder 'downloads'
            file_put_contents($decryptedFileName, $decryptedData);

            echo "<div class='alert alert-success flex items-center space-x-2 p-4 mb-4 text-sm text-green-800 bg-green-100 rounded-lg' role='alert'>
                    <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path>
                    </svg>
                    <span><strong>Sukses!</strong> File berhasil didekripsi dan disimpan sebagai <a href='" . $decryptedFileName . "' class='text-blue-600 underline'>Download File Dekripsi</a>.</span>
                  </div>";
        }
    } else {
        echo "<div class='alert alert-danger flex items-center space-x-2 p-4 mb-4 text-sm text-red-800 bg-red-100 rounded-lg' role='alert'>
                <span><strong>Error!</strong> Terjadi kesalahan dalam meng-upload file.</span>
              </div>";
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/flowbite@1.6.1/dist/flowbite.min.css" rel="stylesheet">
