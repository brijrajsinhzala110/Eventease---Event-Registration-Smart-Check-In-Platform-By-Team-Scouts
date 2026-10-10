<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_roles(['ORGANIZER', 'ADMIN']);

$stmt = $pdo->prepare("SELECT id, title FROM events");
$stmt->execute();
$events = $stmt->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>
<div class="container py-4">
    <h1 class="mb-4 fw-bold">Check-in Scanner</h1>
    <div class="mb-4">
        <label class="form-label fw-bold mt-3">Select Event:</label>
        <select id="event_select" class="border p-2 w-full max-w-sm">
            <option value="">-- Select Event --</option>
            <?php foreach ($events as $event): ?>
            <option value="<?= $event['id'] ?>"><?= htmlspecialchars($event['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div id="reader" style="width: 500px; max-width: 100%;"></div>
    <div id="result" class="mt-4 p-4 border hidden"></div>
</div>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    const html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: 250 });

    function onScanSuccess(decodedText, decodedResult) {
        const eventId = document.getElementById('event_select').value;
        if (!eventId) {
            alert("Please select an event first!");
            return;
        }

        fetch('/eventease-php/api/checkin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                event_id: eventId,
                ticket_code: decodedText
            })
        })
        .then(response => response.json())
        .then(data => {
            const resDiv = document.getElementById('result');
            resDiv.style.display = 'block';
            if (data.success) {
                resDiv.className = 'mt-4 p-4 border bg-green-100 text-green-800';
                resDiv.innerText = "Check-in successful!";
            } else {
                resDiv.className = 'mt-4 p-4 border bg-red-100 text-red-800';
                resDiv.innerText = "Error: " + data.message;
            }
        });
    }

    html5QrcodeScanner.render(onScanSuccess);
</script>
<?php include '../includes/footer.php'; ?>
