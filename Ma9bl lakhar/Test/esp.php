<?php
    require_once 'database.php';

    // Create an instance of the Database class
    $database = new Database();
    $db = $database->getConnection();

    

    // Check if all required GET parameters are available
    if (isset($_GET['check'])) {

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            // Prepare and execute the SQL query
            $sql = "INSERT INTO users_logs (fingerprint_id) VALUES (?)";
            try {
                $stmt = $db->prepare($sql);
                $stmt->execute([$id]);
                echo "User found.";
            } catch (PDOException $e) {
                echo "database error: " . $e->getMessage();
            }
        } else {
            echo "Missing required parameters.";
        }

    }

?>