<?php
session_start();

if (empty($_SESSION['lecturerID']) && empty($_SESSION['studentID'])) {
	header('Location: ../login.php');
	exit;
}

// Demo consultation bookings. Replace this sample data with database results when available.
$bookings = [
	[
		'id' => 'CB-0142',
		'consultant' => 'Dr. Maria Santos',
		'department' => 'Academic Advising',
		'date' => '2025-04-22',
		'time' => '10:30 AM',
		'type' => 'Academic consultation',
		'status' => 'Confirmed',
		'notes' => 'Discuss course selection for the next semester.',
	],
	[
		'id' => 'CB-0137',
		'consultant' => 'Mr. Daniel Cruz',
		'department' => 'Student Affairs',
		'date' => '2025-04-15',
		'time' => '2:00 PM',
		'type' => 'Student support consultation',
		'status' => 'Pending',
		'notes' => 'Follow up regarding available student support services.',
	],
	[
		'id' => 'CB-0098',
		'consultant' => 'Ms. Angela Reyes',
		'department' => 'Career Services',
		'date' => '2025-03-28',
		'time' => '9:00 AM',
		'type' => 'Career consultation',
		'status' => 'Completed',
		'notes' => 'Review internship opportunities and resume.',
	],
];

function h($value)
{
	return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>My Consultation Bookings</title>
	<style>
		* { box-sizing: border-box; }
		body { margin: 0; background: #f3f6fa; color: #263445; font: 15px/1.5 Arial, sans-serif; }
		header { padding: 18px 24px; background: #1d3557; color: #fff; }
		.wrap { width: min(960px, calc(100% - 32px)); margin: 0 auto; }
		header .wrap { display: flex; justify-content: space-between; align-items: center; gap: 16px; }
		.brand { margin: 0; font-weight: bold; }
		header a { color: #fff; text-decoration: none; }
		main { padding: 36px 0; }
		h1 { margin: 0 0 5px; color: #1d3557; font-size: 28px; }
		.intro { margin: 0 0 22px; color: #64748b; }
		.booking { margin-bottom: 16px; overflow: hidden; background: #fff; border: 1px solid #dfe6ee; border-radius: 9px; box-shadow: 0 3px 12px #1d35570b; }
		.booking-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 14px 18px; border-bottom: 1px solid #e8edf3; }
		.booking-id { color: #64748b; font-size: 13px; }
		.status { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
		.confirmed { background: #e7f6ec; color: #247342; }
		.pending { background: #fff3d4; color: #805d00; }
		.completed { background: #e9effb; color: #345b9a; }
		.booking-body { padding: 16px 18px 18px; }
		h2 { margin: 0 0 14px; color: #1d3557; font-size: 18px; }
		.details { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
		.label { display: block; color: #64748b; font-size: 12px; }
		.value { font-weight: 600; }
		.notes { margin: 16px 0 0; padding-top: 12px; border-top: 1px solid #edf1f5; }
		.demo { margin-top: 20px; color: #64748b; font-size: 12px; text-align: center; }
		@media (max-width: 600px) { .details { grid-template-columns: 1fr 1fr; } header .wrap { align-items: flex-start; flex-direction: column; } }
		@media (max-width: 380px) { .details { grid-template-columns: 1fr; } }
	</style>
</head>
<body>
	<header>
		<div class="wrap">
			<p class="brand">Student Consultation Portal</p>
			<a href="dashboard.php">&larr; Back to dashboard</a>
		</div>
	</header>
	<main class="wrap">
		<h1>My Consultation Bookings</h1>
		<p class="intro">View the details and current status of your consultation appointments.</p>

		<?php foreach ($bookings as $booking): ?>
			<article class="booking">
				<div class="booking-head">
					<span class="booking-id">Booking <?= h($booking['id']) ?></span>
					<span class="status <?= h(strtolower($booking['status'])) ?>"><?= h($booking['status']) ?></span>
				</div>
				<div class="booking-body">
					<h2><?= h($booking['type']) ?></h2>
					<div class="details">
						<div><span class="label">Consultant</span><span class="value"><?= h($booking['consultant']) ?></span></div>
						<div><span class="label">Department</span><span class="value"><?= h($booking['department']) ?></span></div>
						<div><span class="label">Date and time</span><span class="value"><?= h(date('M j, Y', strtotime($booking['date']))) ?>, <?= h($booking['time']) ?></span></div>
					</div>
					<p class="notes"><strong>Notes:</strong> <?= h($booking['notes']) ?></p>
				</div>
			</article>
		<?php endforeach; ?>

		<p class="demo">Demo view — all bookings shown are sample data.</p>
	</main>
</body>
</html>
