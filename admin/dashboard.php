<?php
session_start();
include "../config/database.php";

// proteksi admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$title = "Dashboard Admin";

// total paslon
$total_paslon = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM paslon")
)['total'];

// total user
$total_user = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='user'")
)['total'];

// total suara
$total_vote = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM votes")
)['total'];

// user yang sudah vote
$sudah_vote = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='user' AND sudah_vote=1")
)['total'];

// user belum vote
$belum_vote = $total_user - $sudah_vote;

// persentase voting
$persentase_vote = $total_user > 0 ? round(($sudah_vote / $total_user) * 100, 1) : 0;

// data grafik
$grafik = mysqli_query($conn, "
    SELECT 
        paslon.id,
        paslon.nama_ketua,
        paslon.nama_wakil,
        COUNT(votes.id) AS jumlah
    FROM paslon
    LEFT JOIN votes ON paslon.id = votes.paslon_id
    GROUP BY paslon.id
    ORDER BY jumlah DESC
");


// siapkan data untuk chart
$labels = [];
$data = [];
$colors = [
    'rgba(59, 130, 246, 0.8)',
    'rgba(139, 92, 246, 0.8)',
    'rgba(16, 185, 129, 0.8)',
    'rgba(245, 158, 11, 0.8)',
    'rgba(239, 68, 68, 0.8)',
    'rgba(14, 165, 233, 0.8)',
];
$chart_colors = [];

$counter = 0;
while ($row = mysqli_fetch_assoc($grafik)) {
    $labels[] = $row['nama_ketua'] . " & " . $row['nama_wakil'];
    $data[] = $row['jumlah'];
    $chart_colors[] = $colors[$counter % count($colors)];
    $counter++;
}

include "../layouts/app.php";
include "../layouts/sidebar.php";
?>

<div class="p-4 sm:p-6 lg:p-8 ml-0 lg:ml-64 min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-gray-600 mt-2">Monitor dan kelola data pemilihan secara real-time</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <!-- Total Paslon Card -->
        <div class="bg-white rounded-2xl shadow-lg p-5 sm:p-6 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Paslon</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1"><?= $total_paslon ?></p>
                </div>
            </div>
        </div>

        <!-- Total User Card -->
        <div class="bg-white rounded-2xl shadow-lg p-5 sm:p-6 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl border-l-4 border-indigo-500">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-indigo-100 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12 4a3.5 3.5 0 1 0 0 7a3.5 3.5 0 0 0 0-7ZM6.5 7.5a5.5 5.5 0 1 1 11 0a5.5 5.5 0 0 1-11 0ZM3 19a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v3H3v-3Zm5-3a3 3 0 0 0-3 3v1h14v-1a3 3 0 0 0-3-3H8Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Pemilih</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1"><?= $total_user ?></p>
                </div>
            </div>
        </div>

        <!-- Total Suara Card -->
        <div class="bg-white rounded-2xl shadow-lg p-5 sm:p-6 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Suara Masuk</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1"><?= $total_vote ?></p>
                </div>
            </div>
        </div>

        <!-- Persentase Voting Card -->
        <div class="bg-white rounded-2xl shadow-lg p-5 sm:p-6 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-purple-100 mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Partisipasi</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1"><?= $persentase_vote ?>%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bars -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Voting Progress -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Progress Pemungutan Suara</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Sudah Voting</span>
                        <span><?= $sudah_vote ?> dari <?= $total_user ?> pemilih</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-green-500 h-2.5 rounded-full transition-all duration-500 ease-out" style="width: <?= $persentase_vote ?>%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Belum Voting</span>
                        <span><?= $belum_vote ?> pemilih</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-yellow-500 h-2.5 rounded-full transition-all duration-500 ease-out" style="width: <?= (100 - $persentase_vote) ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
            <h3 class="text-lg font-semibold mb-4">Statistik Cepat</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <p class="text-sm opacity-90">Rata-rata Suara</p>
                    <p class="text-2xl font-bold mt-1">
                        <?= $total_paslon > 0 ? round($total_vote / $total_paslon, 1) : 0 ?>
                    </p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <p class="text-sm opacity-90">Sisa Hak Pilih</p>
                    <p class="text-2xl font-bold mt-1"><?= $belum_vote ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Bar Chart Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Distribusi Suara per Paslon</h3>
                <div class="text-sm text-gray-500">
                    Total: <span class="font-bold text-blue-600"><?= $total_vote ?></span> suara
                </div>
            </div>
            <div class="relative h-72">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <!-- Pie Chart Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Persentase Hasil Pemilihan</h3>
                <div class="text-sm text-gray-500">
                    <?= $total_paslon ?> Paslon
                </div>
            </div>
            <div class="relative h-72">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    const labels = <?= json_encode($labels) ?>;
    const dataSuara = <?= json_encode($data) ?>;
    const chartColors = <?= json_encode($chart_colors) ?>;
    const numericData = dataSuara.map(Number);
    const totalSuara = numericData.reduce((a, b) => a + b, 0);

    // Register plugin
    Chart.register(ChartDataLabels);

    // BAR CHART
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Suara',
                data: dataSuara,
                backgroundColor: chartColors,
                borderColor: chartColors.map(color => color.replace('0.8', '1')),
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#1f2937',
                    bodyColor: '#1f2937',
                    borderColor: '#e5e7eb',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return `Suara: ${context.parsed.y}`;
                        }
                    }
                },
                datalabels: {
                    color: '#1f2937',
                    font: {
                        weight: 'bold'
                    },
                    formatter: function(value) {
                        return value > 0 ? value : '';
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        precision: 0,
                        color: '#6b7280'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6b7280',
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    // PIE CHART
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: numericData,
                backgroundColor: chartColors,
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        color: '#1f2937',
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#1f2937',
                    bodyColor: '#1f2937',
                    borderColor: '#e5e7eb',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const percentage = totalSuara > 0 ? ((value / totalSuara) * 100).toFixed(1) : '0';
                            return `${label}: ${value} suara (${percentage}%)`;
                        }
                    }
                },
                datalabels: {
                    color: '#ffffff',
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    formatter: (value) => {
                        if (totalSuara === 0) return '0%';
                        return ((value / totalSuara) * 100).toFixed(1) + '%';
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>