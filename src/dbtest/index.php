<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../.env'); // Adjust path to .env file if necessary
$dotenv->safeLoad();

function connectToDatabase()
{
    $dbservername = $_ENV['DBSERVERNAME'] ?? '';
    $dbusername = $_ENV['DBUSERNAME'] ?? '';
    $dbpassword = $_ENV['DBPASSWORD'] ?? '';
    $dbname = $_ENV['DBNAME'] ?? '';

    // Create connection
    $connection = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    return $connection;
}

$results = null;
if (isset($_POST['query_data'])) {
    $conn = connectToDatabase();

    // Query data
    $sql = "SELECT * FROM PROJECT_DATA";
    $result = $conn->query($sql);

    if ($result) {
        $results = [];
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    } else {
        $results = ["error" => $conn->error];
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Data Viewer</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .explanation { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; background-color: #f9f9f9; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Project Data Viewer</h1>

    <div class="explanation">
        <p>This page connects to the database using credentials from the <code>.env</code> file and queries all available data from the <code>PROJECT_DATA</code> table. Click the button below to fetch and display the data.</p>
    </div>

    <form method="post">
        <button type="submit" name="query_data">Fetch Project Data</button>
    </form>

    <?php if ($results !== null): ?>
        <h2>Query Results:</h2>
        <?php if (isset($results['error'])): ?>
            <p style="color: red;">Error: <?php echo htmlspecialchars($results['error']); ?></p>
        <?php elseif (empty($results)): ?>
            <p>No data found in the PROJECT_DATA table.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <?php foreach (array_keys($results[0]) as $key): ?>
                            <th><?php echo htmlspecialchars($key); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <?php foreach ($row as $value): ?>
                                <td><?php echo htmlspecialchars($value); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>

</body>
</html>
