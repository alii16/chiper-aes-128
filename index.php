<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enkripsi dan Dekripsi File Word dan PDF menggunakan AES-128</title>
    <!-- Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@1.5.0/dist/flowbite.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans py-8">

    <div class="max-w-lg mx-auto py-8 px-4">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-indigo-600 text-center mb-6">Enkripsi dan Dekripsi File Word dan PDF
                menggunakan AES-128</h2>

            <form action="process.php" method="POST" enctype="multipart/form-data">

                <!-- Pilih File -->
                <div class="mb-6">
                    <label for="file" class="block text-sm font-medium text-gray-700">Pilih file (PDF atau
                        Word):</label>
                    <input type="file" name="file" id="file" accept=".pdf, .docx"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>
                <div class="grid cols-1 sm:grid-cols-2 gap-6 lg:gap-0">
                    <!-- Pilih Aksi -->
                    <div class="mb-6">
                        <label for="action" class="block text-sm font-medium text-gray-700">Pilih aksi:</label>
                        <select name="action" id="action"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                            <option value="encrypt">Enkripsi</option>
                            <option value="decrypt">Dekripsi</option>
                        </select>
                    </div>

                    <!-- Kunci AES -->
                    <div class="mb-6">
                        <label for="key" class="block text-sm font-medium text-gray-700">Masukkan Kunci AES:</label>
                        <input type="text" name="key" id="key" maxlength="16" placeholder="16 karakter"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                    </div>

                </div>

                <!-- Tombol Proses -->
                <button type="submit"
                    class="w-full py-3 px-4 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                    Proses
                </button>
            </form>
        </div>

        <div class="text-center mt-8">
            <p class="text-sm text-gray-500">Made with 💡 by Ali Polanunu</p>
        </div>
    </div>

    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.5.0/dist/flowbite.min.js"></script>
</body>

</html>