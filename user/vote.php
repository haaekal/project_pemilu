<?php
session_start();
include "../config/database.php";

// PROTEKSI LOGIN USER
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}

$title = "Voting Paslon";

$user_id = (int) $_SESSION['user_id'];

// AMBIL STATUS USER (REALTIME)
$userQuery = mysqli_query($conn, "SELECT sudah_vote FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($userQuery);

// PROSES VOTING
if (isset($_POST['vote'])) {

    // AMBIL ULANG STATUS (ANTI DOUBLE TAB / SPAM)
    $cekUser = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT sudah_vote FROM users WHERE id = $user_id")
    );

    if ($cekUser['sudah_vote'] == 1) {
        header("Location: vote.php");
        exit;
    }

    $paslon_id = (int) $_POST['paslon_id'];

    // CEK PASLON VALID
    $cekPaslon = mysqli_num_rows(
        mysqli_query($conn, "SELECT id FROM paslon WHERE id = $paslon_id")
    );

    if ($cekPaslon === 0) {
        die("Paslon tidak valid");
    }

    // TRANSAKSI (ANTI GAGAL SEBAGIAN)
    mysqli_begin_transaction($conn);

    try {
        mysqli_query($conn, "
            INSERT INTO votes (user_id, paslon_id)
            VALUES ($user_id, $paslon_id)
        ");

        mysqli_query($conn, "
            UPDATE users SET sudah_vote = 1 WHERE id = $user_id
        ");

        mysqli_commit($conn);

        $_SESSION['vote_success'] = true;
        header("Location: vote.php");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        die("Terjadi kesalahan saat voting");
    }
}

// DATA PASLON
$paslon = mysqli_query($conn, "SELECT * FROM paslon");

include "../layouts/app.php";
?>

<div class="min-h-screen bg-gradient-to-b from-blue-50 to-indigo-100 p-4 md:p-6">
    <!-- Header dengan Background Pattern -->
    <div class="max-w-7xl mx-auto mb-8">
        <!-- Status Info Bar -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 mb-6 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute top-0 right-0 w-32 h-32 opacity-10">
                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                </svg>
            </div>

            <div class="relative z-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                            🗳️ Pemilihan Ketua & Wakil Ketua
                        </h1>
                        <p class="text-blue-100">
                            Gunakan hak pilihmu dengan bijak. Pilih pasangan calon yang tepat!
                        </p>
                    </div>

                    <!-- User Status Badge -->
                    <div class="mt-4 md:mt-0">
                        <div class="inline-flex items-center bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full">
                            <?php if ($user['sudah_vote']): ?>
                                <span class="flex items-center text-white">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Sudah Voting
                                </span>
                            <?php else: ?>
                                <span class="flex items-center text-white">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Belum Voting
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($user['sudah_vote']): ?>
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-4 rounded-xl shadow-lg mb-6 transform transition-all duration-300 hover:scale-[1.02]">
                <div class="flex items-center">
                    <div class="bg-white/20 p-2 rounded-lg mr-4">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Voting Berhasil!</h3>
                        <p class="text-green-100">Terima kasih telah menggunakan hak pilihmu dengan baik.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- DAFTAR PASLON -->
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Kandidat Pasangan Calon</h2>
            <p class="text-gray-600">Pilih salah satu pasangan calon di bawah ini</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($paslon)) : ?>
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-blue-100">
                    <!-- Card Header dengan Gradien -->
                    <div class="relative h-56 overflow-hidden">
                        <img
                            src="../uploads/paslon/<?= $row['foto'] ?>"
                            alt="Foto <?= $row['nama_ketua'] ?>"
                            class="w-full h-full object-cover"
                            onerror="this.src='../assets/img/default-user.png'">

                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>

                        <!-- Label -->
                        <div class="absolute top-3 left-3 bg-white/90 px-3 py-1 rounded-full text-sm font-bold text-blue-600 shadow">
                            PASLON <?= $no ?>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-white/80 text-sm">Pasangan Calon</span>
                                <h3 class="text-xl font-bold text-white">
                                    <?= $row['nama_ketua'] ?> & <?= $row['nama_wakil'] ?>
                                </h3>
                            </div>
                            <div class="bg-white/20 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-5">
                        <!-- Visi Section -->
                        <div class="mb-5">
                            <div class="flex items-center mb-2">
                                <div class="bg-blue-100 p-1.5 rounded-lg mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-800">Visi</h4>
                            </div>
                            <p class="text-gray-600 text-sm pl-9 leading-relaxed">
                                <?= nl2br(htmlspecialchars($row['visi'])) ?>
                            </p>
                        </div>

                        <!-- Misi Section -->
                        <div class="mb-6">
                            <div class="flex items-center mb-2">
                                <div class="bg-indigo-100 p-1.5 rounded-lg mr-3">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-800">Misi</h4>
                            </div>
                            <p class="text-gray-600 text-sm pl-9 leading-relaxed">
                                <?= nl2br(htmlspecialchars($row['misi'])) ?>
                            </p>
                        </div>

                        <!-- Action Button -->
                        <?php if (!$user['sudah_vote']): ?>
                            <button
                                onclick='openModal(
                                    <?= json_encode($row["id"]) ?>,
                                    <?= json_encode($row["nama_ketua"]) ?>,
                                    <?= json_encode($row["nama_wakil"]) ?>,
                                    <?= json_encode($row["visi"]) ?>,
                                    <?= json_encode($row["misi"]) ?>,
                                    <?= json_encode($row["foto"]) ?>
                                )'
                                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold py-3 rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-95 shadow-md hover:shadow-lg flex items-center justify-center group">
                                <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                PILIH PASLON INI
                            </button>
                        <?php else: ?>
                            <div class="w-full bg-gray-100 text-gray-500 font-semibold py-3 rounded-xl flex items-center justify-center cursor-not-allowed">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                SUDAH VOTING
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $no++; ?>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI -->
<div id="modal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">

    <!-- BOX MODAL -->
    <div
        class="bg-white rounded-2xl w-full max-w-md max-h-[90vh] overflow-hidden animate-modal-in flex flex-col">

        <!-- HEADER -->
        <div
            class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 shrink-0">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Konfirmasi Pilihan</h2>
                    <p class="text-blue-100 text-sm mt-1">
                        Pastikan pilihan Anda sudah tepat
                    </p>
                </div>
                <button onclick="closeModal()"
                    class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- CONTENT (SCROLLABLE) -->
        <div class="flex-1 overflow-y-auto p-6">
            <!-- FOTO PASLON -->
            <div class="mb-6 text-center">
                <div
                    class="w-32 h-32 mx-auto rounded-full overflow-hidden shadow-lg border-4 border-blue-100 mb-4">
                    <img id="modalFoto" src=""
                        class="w-full h-full object-cover"
                        alt="Foto Paslon">
                </div>

                <h3 class="text-lg font-semibold text-gray-800 mb-2"
                    id="modalNama"></h3>
                <p class="text-gray-600 text-sm">
                    Anda akan memilih pasangan calon ini
                </p>
            </div>

            <!-- VISI & MISI -->
            <div class="space-y-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-xl">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                        <span class="font-medium text-gray-700">Visi</span>
                    </div>
                    <p id="modalVisi" class="whitespace-pre-line text-gray-600 text-sm"></p>
                </div>

                <div class="bg-indigo-50 p-4 rounded-xl">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="font-medium text-gray-700">Misi</span>
                    </div>
                    <p id="modalMisi" class="whitespace-pre-line text-gray-600 text-sm"></p>
                </div>
            </div>

            <!-- WARNING -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-24">
                <p class="text-sm text-yellow-700">
                    <strong>Peringatan:</strong>
                    Pilihan tidak dapat diubah setelah dikonfirmasi.
                </p>
            </div>
        </div>

        <!-- FOOTER (STICKY) -->
        <div
            class="sticky bottom-0 bg-white p-5 border-t shadow-[0_-6px_20px_-8px_rgba(0,0,0,0.2)]">

            <form method="POST">
                <input type="hidden" name="paslon_id" id="modalPaslonId">

                <button type="submit" name="vote"
                    class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-3.5 rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-95 shadow-md hover:shadow-lg flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                    KONFIRMASI PILIHAN
                </button>
            </form>
        </div>

    </div>
</div>


<?php if (isset($_SESSION['vote_success'])): ?>
    <!-- SUCCESS MODAL -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl p-8 text-center max-w-sm w-full animate-modal-in">
            <!-- Animated Checkmark -->
            <div class="relative mx-auto mb-6 w-24 h-24">
                <div class="absolute inset-0 border-4 border-green-200 rounded-full"></div>
                <div class="absolute inset-4 border-4 border-green-500 rounded-full animate-ping opacity-75"></div>
                <div class="absolute inset-4 flex items-center justify-center">
                    <svg class="w-16 h-16 text-green-500 animate-checkmark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                Voting Berhasil!
            </h2>

            <p class="text-gray-600 mb-6">
                Terima kasih telah menggunakan hak pilihmu dengan bijak.
            </p>

            <div class="bg-blue-50 rounded-xl p-4 mb-6">
                <p class="text-sm text-blue-700">
                    <strong>Info:</strong> Kamu akan otomatis logout dalam
                </p>
                <div class="mt-2 text-3xl font-bold text-blue-600" id="countdown">3</div>
            </div>

            <div class="text-xs text-gray-500">
                Jangan Lupa Sholat
            </div>
        </div>
    </div>

    <script>
        // Countdown Timer
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');

        const countdownInterval = setInterval(() => {
            countdown--;
            if (countdown > 0) {
                countdownElement.textContent = countdown;
                // Add pulse animation
                countdownElement.classList.add('scale-125');
                setTimeout(() => {
                    countdownElement.classList.remove('scale-125');
                }, 200);
            } else {
                clearInterval(countdownInterval);
                window.location.href = "../auth/logout.php";
            }
        }, 1000);
    </script>

    <?php unset($_SESSION['vote_success']); ?>
<?php endif; ?>

<script>
    function openModal(id, ketua, wakil, visi, misi, foto) {
        const modal = document.getElementById('modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('modalNama').textContent = ketua + ' & ' + wakil;
        document.getElementById('modalVisi').textContent = visi;
        document.getElementById('modalMisi').textContent = misi;
        document.getElementById('modalPaslonId').value = id;
        document.getElementById('modalFoto').src = '../uploads/paslon/' + foto;

        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        // Restore scrolling
        document.body.style.overflow = 'auto';
    }

    // Close modal on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Close modal when clicking outside
    document.getElementById('modal')?.addEventListener('click', (e) => {
        if (e.target.id === 'modal') {
            closeModal();
        }
    });
</script>

<style>
    /* Animations using only inline styles */
    .animate-modal-in {
        animation: modalIn 0.3s ease-out;
    }

    .animate-checkmark {
        animation: checkmarkDraw 0.5s ease-out;
        stroke-dasharray: 24;
        stroke-dashoffset: 24;
        animation-fill-mode: forwards;
        animation-delay: 0.2s;
    }

    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(20px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    @keyframes checkmarkDraw {
        to {
            stroke-dashoffset: 0;
        }
    }
</style>