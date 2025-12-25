<?php
session_start();
include "../../config/database.php";

// proteksi admin
if ($_SESSION['role'] != 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

$title = "Manajemen User";

// ambil data user (role user saja)
$users = mysqli_query($conn, "SELECT * FROM users WHERE role='user'");

// mode edit
$edit = false;
$dataEdit = null;

if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $dataEdit = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT * FROM users WHERE id=$id")
    );
}

include "../../layouts/app.php";
include "../../layouts/sidebar.php";
?>

<div class="p-4 sm:p-6 lg:p-8 ml-0 lg:ml-64 min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen User</h1>
                <p class="text-gray-600 mt-2">Kelola data siswa yang berhak memilih dalam pemilihan</p>
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

    <!-- FORM TAMBAH / EDIT -->
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
                        <?= $edit ? "Edit Data Siswa" : "Tambah Siswa Baru" ?>
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">
                        <?= $edit ? "Perbarui data siswa" : "Isi form untuk menambahkan siswa baru" ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" action="<?= $edit ? 'edit.php' : 'tambah.php' ?>" class="space-y-6">
                <?php if ($edit): ?>
                    <input type="hidden" name="id" value="<?= $dataEdit['id'] ?>">
                <?php endif; ?>

                <!-- Data Dasar -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Inisial Nama -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Inisial Nama
                            </span>
                        </label>
                        <input type="text"
                            name="inisial_nama"
                            placeholder="Contoh: ASEP"
                            value="<?= $edit ? htmlspecialchars($dataEdit['inisial_nama']) : '' ?>"
                            required
                            class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder:text-gray-400">
                    </div>

                    <!-- NIM -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                </svg>
                                NIM
                            </span>
                        </label>
                        <input type="text"
                            name="nim"
                            placeholder="Contoh: 251351001"
                            value="<?= $edit ? htmlspecialchars($dataEdit['nim']) : '' ?>"
                            required
                            class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all duration-200 placeholder:text-gray-400">
                    </div>

                    <!-- Kelas -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Kelas
                            </span>
                        </label>
                        <input type="text"
                            name="kelas"
                            placeholder="Contoh: PAGI A"
                            value="<?= $edit ? htmlspecialchars($dataEdit['kelas']) : '' ?>"
                            required
                            class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 transition-all duration-200 placeholder:text-gray-400">
                    </div>
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Password
                            <?php if ($edit): ?>
                                <span class="ml-2 text-xs font-normal text-gray-500">(kosongkan jika tidak diubah)</span>
                            <?php endif; ?>
                        </span>
                    </label>
                    <div class="relative">
                        <input type="password"
                            name="password"
                            id="passwordInput"
                            <?php if (!$edit): ?>required<?php endif; ?>
                            placeholder="<?= $edit ? 'Password baru (opsional)' : 'Masukkan password' ?>"
                            class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-200 transition-all duration-200 placeholder:text-gray-400 pr-12">
                        <button type="button"
                            id="togglePassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-amber-500 transition-colors duration-200 focus:outline-none">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <?php if ($edit): ?>
                        <p class="text-xs text-gray-500 mt-1">
                            Biarkan kosong untuk mempertahankan password saat ini
                        </p>
                    <?php else: ?>
                        <p class="text-xs text-gray-500 mt-1">
                            Minimal 6 karakter. Password akan di-hash sebelum disimpan
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 pt-4">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-95 shadow-lg hover:shadow-xl">
                        <?php if ($edit): ?>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Data
                        <?php else: ?>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Siswa
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

    <!-- TABEL USER -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-blue-100">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="p-3 bg-white/20 rounded-xl mr-4">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path d="M17.9998 15.8369C19.4557 16.5683 20.704 17.742 21.6151 19.2096C21.7955 19.5003 21.8857 19.6456 21.9169 19.8468C21.9803 20.2558 21.7006 20.7585 21.3198 20.9204C21.1323 21 20.9215 21 20.4998 21M15.9998 11.5322C17.4816 10.7959 18.4998 9.26686 18.4998 7.5C18.4998 5.73314 17.4816 4.20411 15.9998 3.46776M13.9998 7.5C13.9998 9.98528 11.9851 12 9.49984 12C7.01455 12 4.99984 9.98528 4.99984 7.5C4.99984 5.01472 7.01455 3 9.49984 3C11.9851 3 13.9998 5.01472 13.9998 7.5ZM2.55907 18.9383C4.15337 16.5446 6.66921 15 9.49984 15C12.3305 15 14.8463 16.5446 16.4406 18.9383C16.7899 19.4628 16.9645 19.725 16.9444 20.0599C16.9287 20.3207 16.7578 20.64 16.5494 20.7976C16.2818 21 15.9137 21 15.1775 21H3.82219C3.08601 21 2.71791 21 2.45028 20.7976C2.24189 20.64 2.07092 20.3207 2.05527 20.0599C2.03517 19.725 2.2098 19.4628 2.55907 18.9383Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Daftar Siswa</h2>
                        <p class="text-blue-100 text-sm mt-1">
                            <?php
                            $total_users = mysqli_num_rows($users);
                            $voted_users = mysqli_fetch_assoc(
                                mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user' AND sudah_vote=1")
                            )['total'];
                            mysqli_data_seek($users, 0); // Reset pointer
                            echo "$total_users siswa | $voted_users sudah voting";
                            ?>
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="bg-white/20 text-white text-sm px-3 py-1 rounded-full">
                        Total: <?= $total_users ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-6 bg-gray-50">
            <?php
            $sudah_vote = mysqli_fetch_assoc(
                mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user' AND sudah_vote=1")
            )['total'];
            $belum_vote = $total_users - $sudah_vote;
            $persentase = $total_users > 0 ? round(($sudah_vote / $total_users) * 100, 1) : 0;
            ?>

            <div class="bg-white rounded-xl p-4 shadow border border-blue-100">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Sudah Voting</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $sudah_vote ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow border border-yellow-100">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Belum Voting</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $belum_vote ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow border border-blue-100">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Partisipasi</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $persentase ?>%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
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
                            Siswa
                        </th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Kelas
                        </th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($users)) : ?>
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <span class="text-gray-900 font-medium"><?= $no++ ?></span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-semibold">
                                        <?= substr($row['inisial_nama'], 0, 2) ?>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($row['inisial_nama']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            NIM: <?= $row['nim'] ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <?= htmlspecialchars($row['kelas']) ?>
                                </span>
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
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')"
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

                    <?php if (mysqli_num_rows($users) == 0): ?>
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 0h-6" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data siswa</h3>
                                    <p class="text-gray-500">Tambahkan siswa pertama Anda menggunakan form di atas</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer Table -->
        <?php if (mysqli_num_rows($users) > 0): ?>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between">
                    <div class="text-sm text-gray-600 mb-2 sm:mb-0">
                        Menampilkan <span class="font-medium"><?= $total_users ?></span> siswa
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('passwordInput');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
                eyeIcon.classList.add('text-amber-500');
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
                eyeIcon.classList.remove('text-amber-500');
            }
        });
    }
</script>