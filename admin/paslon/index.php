<?php
session_start();
include "../../config/database.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

$title = "CRUD Paslon";

$paslon = mysqli_query($conn, "SELECT * FROM paslon");

$edit = false;
$dataEdit = null;

if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $dataEdit = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT * FROM paslon WHERE id=$id")
    );
}

include "../../layouts/app.php";
include "../../layouts/sidebar.php";
?>

<div class="p-4 sm:p-6 lg:p-8 ml-0 lg:ml-64 min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Paslon</h1>
                <p class="text-gray-600 mt-2">Kelola data pasangan calon Ketua & Wakil Ketua</p>
            </div>
            <?php if ($edit): ?>
                <div class="mt-4 sm:mt-0">
                    <a href="index.php" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batalkan Edit
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl mb-8 overflow-hidden border border-blue-100">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-xl mr-4">
                    <?php if ($edit): ?>
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    <?php else: ?>
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    <?php endif; ?>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">
                        <?= $edit ? "Edit Pasangan Calon" : "Tambah Pasangan Calon Baru" ?>
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">
                        <?= $edit ? "Perbarui data pasangan calon" : "Isi form untuk menambahkan pasangan calon baru" ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" action="<?= $edit ? 'edit.php' : 'tambah.php' ?>" enctype="multipart/form-data" class="space-y-6">
                <?php if ($edit): ?>
                    <input type="hidden" name="id" value="<?= $dataEdit['id'] ?>">
                    <input type="hidden" name="foto_lama" value="<?= $dataEdit['foto'] ?>">
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Nama Ketua
                            </span>
                        </label>
                        <input type="text"
                            name="nama_ketua"
                            placeholder="Masukkan nama ketua"
                            value="<?= $edit ? htmlspecialchars($dataEdit['nama_ketua']) : '' ?>"
                            required
                            class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder:text-gray-400">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-indigo-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M17.9998 15.8369C19.4557 16.5683 20.704 17.742 21.6151 19.2096C21.7955 19.5003 21.8857 19.6456 21.9169 19.8468C21.9803 20.2558 21.7006 20.7585 21.3198 20.9204C21.1323 21 20.9215 21 20.4998 21M15.9998 11.5322C17.4816 10.7959 18.4998 9.26686 18.4998 7.5C18.4998 5.73314 17.4816 4.20411 15.9998 3.46776M13.9998 7.5C13.9998 9.98528 11.9851 12 9.49984 12C7.01455 12 4.99984 9.98528 4.99984 7.5C4.99984 5.01472 7.01455 3 9.49984 3C11.9851 3 13.9998 5.01472 13.9998 7.5ZM2.55907 18.9383C4.15337 16.5446 6.66921 15 9.49984 15C12.3305 15 14.8463 16.5446 16.4406 18.9383C16.7899 19.4628 16.9645 19.725 16.9444 20.0599C16.9287 20.3207 16.7578 20.64 16.5494 20.7976C16.2818 21 15.9137 21 15.1775 21H3.82219C3.08601 21 2.71791 21 2.45028 20.7976C2.24189 20.64 2.07092 20.3207 2.05527 20.0599C2.03517 19.725 2.2098 19.4628 2.55907 18.9383Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </g>
                                </svg>
                                Nama Wakil
                            </span>
                        </label>
                        <input type="text"
                            name="nama_wakil"
                            placeholder="Masukkan nama wakil"
                            value="<?= $edit ? htmlspecialchars($dataEdit['nama_wakil']) : '' ?>"
                            required
                            class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 placeholder:text-gray-400">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe-icon lucide-globe">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                <path d="M2 12h20" />
                            </svg>
                            Visi
                        </span>
                    </label>
                    <textarea name="visi"
                        rows="3"
                        placeholder="Tulis visi pasangan calon"
                        required
                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition-all duration-200 resize-none placeholder:text-gray-400"><?= $edit ? htmlspecialchars($dataEdit['visi']) : '' ?></textarea>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Misi
                        </span>
                    </label>
                    <textarea name="misi"
                        rows="4"
                        placeholder="Tulis misi pasangan calon"
                        required
                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 resize-none placeholder:text-gray-400"><?= $edit ? htmlspecialchars($dataEdit['misi']) : '' ?></textarea>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Foto Pasangan Calon
                        </span>
                    </label>

                    <?php if ($edit && $dataEdit['foto']): ?>
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Foto saat ini:</p>
                            <div class="inline-block relative">
                                <img src="../../uploads/paslon/<?= $dataEdit['foto'] ?>"
                                    class="w-32 h-32 object-cover rounded-xl shadow-lg border-2 border-blue-200">
                                <div class="absolute bottom-2 right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded">
                                    Saat ini
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mb-2">Upload foto baru untuk mengganti:</p>
                    <?php endif; ?>

                    <div class="relative">
                        <input type="file"
                            name="foto"
                            accept="image/*"
                            class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-200 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <p class="text-xs text-gray-500">Format: JPG, PNG, JPEG. Maksimal 2MB</p>
                </div>

                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 pt-4">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-95 shadow-lg hover:shadow-xl">
                        <?php if ($edit): ?>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Paslon
                        <?php else: ?>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Paslon
                        <?php endif; ?>
                    </button>

                    <?php if ($edit): ?>
                        <a href="index.php"
                            class="inline-flex items-center justify-center px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-blue-100">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-3 bg-white/20 rounded-xl mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Daftar Pasangan Calon</h2>
                        <p class="text-blue-100 text-sm mt-1">
                            <?php
                            $total = mysqli_num_rows($paslon);
                            mysqli_data_seek($paslon, 0);
                            echo "$total pasangan calon terdaftar";
                            ?>
                        </p>
                    </div>
                </div>
                <div class="hidden sm:block">
                    <span class="bg-white/20 text-white text-sm px-3 py-1 rounded-full">
                        Total: <?= $total ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center">
                                <span>#</span>
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Foto
                        </th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Pasangan Calon
                        </th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Visi & Misi
                        </th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($paslon)) : ?>
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <span class="text-gray-900 font-medium"><?= $no++ ?></span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <?php if ($row['foto']): ?>
                                    <div class="relative">
                                        <img src="../../uploads/paslon/<?= $row['foto'] ?>"
                                            class="w-16 h-16 object-cover rounded-xl shadow border-2 border-blue-100">
                                    </div>
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6">
                                <div>
                                    <p class="font-medium text-gray-900"><?= htmlspecialchars($row['nama_ketua']) ?></p>
                                    <p class="text-sm text-gray-600">Ketua</p>
                                </div>
                                <div class="mt-2">
                                    <p class="font-medium text-gray-900"><?= htmlspecialchars($row['nama_wakil']) ?></p>
                                    <p class="text-sm text-gray-600">Wakil</p>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="max-w-xs">
                                    <div class="mb-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe-icon lucide-globe">
                                                <circle cx="12" cy="12" r="10" />
                                                <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                                <path d="M2 12h20" />
                                            </svg>
                                            Visi
                                        </span>
                                        <p class="text-sm text-gray-600 mt-1 truncate"><?= htmlspecialchars(substr($row['visi'], 0, 60)) ?>...</p>
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            Misi
                                        </span>
                                        <p class="text-sm text-gray-600 mt-1 truncate"><?= htmlspecialchars(substr($row['misi'], 0, 60)) ?>...</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex space-x-2">
                                    <a href="?edit=<?= $row['id'] ?>"
                                        class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <a href="hapus.php?id=<?= $row['id'] ?>"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pasangan calon ini?')"
                                        class="inline-flex items-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                    <?php if (mysqli_num_rows($paslon) == 0): ?>
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 0h-6" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data paslon</h3>
                                    <p class="text-gray-500">Tambahkan pasangan calon pertama Anda menggunakan form di atas</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (mysqli_num_rows($paslon) > 0): ?>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan <span class="font-medium"><?= $total ?></span> pasangan calon
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Terakhir diupdate:</span>
                        <span class="text-sm font-medium text-blue-600"><?= date('d M Y H:i') ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>