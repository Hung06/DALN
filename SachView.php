<?php
$uri = "mysql://avnadmin:AVNS_DWVzerNSF2ajNoxaGFD@mysql-3919f417-st-b147.c.aivencloud.com:18710/TEST1?ssl-mode=REQUIRED";
$fields = parse_url($uri);

// build the DSN including SSL settings
$conn = "mysql:";
$conn .= "host=" . $fields["host"];
$conn .= ";port=" . $fields["port"];
$conn .= ";dbname=TEST1";
$conn .= ";sslmode=verify-ca;sslrootcert=ca.pem";

try {
    $db = new PDO($conn, $fields["user"], $fields["pass"]);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->query("SELECT * FROM Sach");
    $Sachs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Books</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>List of Books</h1>
    <table>
        <thead>
            <tr>
                <th>Sach ID</th>
                <th>Ten Sach</th>
                <th>So luong</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($Sachs as $Sach) {
                echo "<tr>";
                echo "<td>" . $Sach['MaSach'] . "</td>";
                echo "<td>" . $Sach['TenSach'] . "</td>";
                echo "<td>" . $Sach['SoLuong'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
