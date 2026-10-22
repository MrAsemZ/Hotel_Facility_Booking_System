<?php
require '../includes/config.php';
require_admin();

// Counts per status for cards and chart
$byStatus = ['pending' => 0, 'confirmed' => 0, 'rejected' => 0, 'cancelled' => 0];
foreach ($pdo->query('SELECT status, COUNT(*) AS total FROM reservations GROUP BY status') as $row) {
    $byStatus[$row['status']] = (int) $row['total'];
}
$totalReservations = array_sum($byStatus);
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();

require '../includes/header.php';
require '../includes/admin_nav.php';
?>
<h1>Dashboard</h1>
<div class="stats">
    <div class="stat"><span><?= $totalReservations ?></span>Total reservations</div>
    <div class="stat"><span><?= $byStatus['pending'] ?></span>Pending</div>
    <div class="stat"><span><?= $byStatus['confirmed'] ?></span>Confirmed</div>
    <div class="stat"><span><?= $totalUsers ?></span>Registered users</div>
</div>

<div class="card chart-box"><canvas id="statusChart"></canvas></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('statusChart'), {
        type: 'bar',
        data: {
            labels: ['Pending', 'Confirmed', 'Rejected', 'Cancelled'],
            datasets: [{
                label: 'Reservations',
                data: <?= json_encode(array_values($byStatus)) ?>,
                backgroundColor: ['#f0ad4e', '#5cb85c', '#d9534f', '#999999']
            }]
        },
        options: { scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });
</script>
<?php require '../includes/footer.php'; ?>
