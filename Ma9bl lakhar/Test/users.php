<?php
    require_once 'database.php';

    // Create an instance of the Database class
    $database = new Database();
    $db = $database->getConnection();

    

    // Check if all required GET parameters are available
    if (isset($_GET['add_user'])) {

        if (isset($_GET['username'], $_GET['fingerprint_id'], $_GET['tache'])) {
            $username = $_GET['username'];
            $fingerprint_id = $_GET['fingerprint_id'];
            $tache = $_GET['tache'];
    
            // Prepare and execute the SQL query
            $sql = "INSERT INTO users (username, fingerprint_id, tache, is_finger_print_saved) VALUES (?, ?, ?, ?)";
            try {
                $stmt = $db->prepare($sql);
                $stmt->execute([$username, $fingerprint_id, $tache, 0]);
                echo "New user added successfully.";
            } catch (PDOException $e) {
                echo "Error adding user: " . $e->getMessage();
            }
        } else {
            echo "Missing required parameters.";
        }

    }

    elseif (isset($_GET['validate_fingerprint'])) {

        if (isset($_GET['fingerprint_id'])) {
            try {

                $fingerprint_id = $_GET['fingerprint_id'];
                
                $stmt = $db->prepare("UPDATE users SET is_finger_print_saved = 1 WHERE fingerprint_id = ".$fingerprint_id);

                // Execute the query
                $stmt->execute();

            } catch (PDOException $e) {
                echo "Error adding user: " . $e->getMessage();
            }

        } else {
            echo "Missing required parameters.";
        }

    }
?>