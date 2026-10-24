<?php
require_once __DIR__ . '/../includes/config.php';

$pageTitle = 'How It Works';
include ROOT . '/includes/header.php';
?>
<h1>How It Works</h1>

<h2>Booking Steps</h2>
<ol>
    <li><a href="<?= url('pages/register.php') ?>">Register</a> and log in.</li>
    <li>Open <a href="<?= url('pages/facilities.php') ?>">Facilities</a> to see what is available and read the guidelines.</li>
    <li>Go to <a href="<?= url('pages/availability.php') ?>">Availability</a>, choose a date (and a facility), and press Reserve on a free slot.</li>
    <li>Follow your request in <a href="<?= url('pages/my_reservations.php') ?>">My Reservations</a>.</li>
</ol>

<h2>Reservation Statuses</h2>
<div class="table-wrap">
<table>
    <tr><th>Status</th><th>Meaning</th></tr>
    <tr><td><span class="badge pending">Pending</span></td><td>Your request was sent and waits for the hotel.</td></tr>
    <tr><td><span class="badge confirmed">Confirmed</span></td><td>Your place is guaranteed.</td></tr>
    <tr><td><span class="badge rejected">Rejected</span></td><td>The hotel could not accept the request.</td></tr>
    <tr><td><span class="badge cancelled">Cancelled</span></td><td>The reservation was cancelled and the place is free again.</td></tr>
</table>
</div>

<h2>Rules</h2>
<ul>
    <li>You cannot book a time slot that already started.</li>
    <li>Each slot has a limited number of places.</li>
    <li>You can book the same slot only once.</li>
    <li>You can cancel your own reservation until the slot begins.</li>
</ul>

<h2>FAQ</h2>
<h3>Do I have to pay online?</h3>
<p>No. This system only reserves your place.</p>
<h3>Can I change a reservation?</h3>
<p>Cancel it and book another slot.</p>
<h3>Why is my reservation still pending?</h3>
<p>The hotel reviews requests manually. Call reception if you need a faster answer.</p>
<?php include ROOT . '/includes/footer.php'; ?>
