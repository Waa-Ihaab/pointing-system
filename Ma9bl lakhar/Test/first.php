<?php
$host = 'localhost';
$dbname = 'espdb';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT u.username, l.fingerprint_id, l.timestamp
              FROM users u
              JOIN users_logs l ON u.fingerprint_id = l.fingerprint_id
              ORDER BY l.timestamp ASC";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

function classifyTime($timestamp) {
    $time = new DateTime($timestamp);
    $hour = $time->format('H:i:s');

    $checkinStart = new DateTime("09:00:00");
    $checkinEnd = new DateTime("09:30:00");
    $checkoutStart = new DateTime("09:30:00");
    $checkoutEnd = new DateTime("16:00:00");

    if ($hour >= $checkinStart->format('H:i:s') && $hour < $checkinEnd->format('H:i:s')) {
        return 'Check-in';
    } elseif ($hour >= $checkoutStart->format('H:i:s') && $hour < $checkoutEnd->format('H:i:s')) {
        return 'Check-out';
    } else {
        return 'N/A'; // or any other appropriate label if the time doesn't fit any criteria
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Check-ins and Check-outs</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; }
        title{font-family: Arial, sans-serif;}
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">User Check-in and Check-out History</h1>
        <!-- Date selection form -->
        <form action="" method="GET">
            <div class="mb-3">
                <label for="date" class="form-label">Select Date:</label>
                <input type="date" class="form-control" id="date" name="date">
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
        
        <br>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Username</th>
                    <th>Tache</th>
                    <th>Timestamp</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if date is selected
                // Check if date is selected
                if(isset($_GET['date']) && !empty($_GET['date'])) {
                    $selectedDate = $_GET['date'];
                    $query = "SELECT u.username, u.tache, l.timestamp
                            FROM users u
                            JOIN users_logs l ON u.fingerprint_id = l.fingerprint_id
                            WHERE DATE(l.timestamp) = :selectedDate
                            ORDER BY l.timestamp ASC";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':selectedDate', $selectedDate);
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    // If no date is selected, fetch all records
                    $query = "SELECT u.username, u.tache, l.timestamp
                            FROM users u
                            JOIN users_logs l ON u.fingerprint_id = l.fingerprint_id
                            ORDER BY l.timestamp ASC";
                    $stmt = $pdo->query($query);
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                foreach ($results as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['tache']) ?></td>
                    <td><?= htmlspecialchars($row['timestamp']) ?></td>
                    <td><?= htmlspecialchars(classifyTime($row['timestamp'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button id="downloadCSV" class="btn btn-success">Download CSV</button>
    </div>
    </div>
    <!-- Include Bootstrap JS and its dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="csv.js"></script>
</body>
</html>
