<?php
require_once __DIR__ . '/../includes/config.php';

(new Auth())->requireAdmin();

$byStatus     = (new Reservation())->countsByStatus();
$totalUsers   = (new User())->countCustomers();

$pageTitle = 'Admin Dashboard';
include ROOT . '/includes/header.php';
include ROOT . '/includes/admin_nav.php';
?>
<h1>Dashboard</h1>
<div class="stats">
    <div class="stat"><span><?= array_sum($byStatus) ?></span>Total reservations</div>
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
<?php include ROOT . '/includes/admin_end.php';
include ROOT . '/includes/footer.php'; ?>
