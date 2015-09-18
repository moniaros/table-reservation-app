<?php
    if(isset($_POST['name']) || isset($_POST)){
        // create databases and open connections
        try {
            $db = new PDO('sqlite:reservations.sqlite3');

            // $db->exec('DROP TABLE IF EXISTS reservation');
            $db->exec("CREATE TABLE IF NOT EXISTS reservation (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        guest_name VARCHAR(100),
                        guest_email VARCHAR(100),
                        guest_tel VARCHAR(100),
                        reservation_date VARCHAR(100),
                        reservation_time VARCHAR(100),
                        guests INTEGER,
                        table_location VARCHAR(20))"
            );

            if($_GET['update'] == "yes"){
                echo "<h1>updating</h1>";
                $query = 'UPDATE reservation
                            SET `guest_name` = :guest_name,
                                `guest_email` = :guest_email,
                                `guest_tel` = :guest_tel,
                                `reservation_date` = :reservation_date,
                                `reservation_time` = :reservation_time,
                                `guests` = :guests,
                                `table_location` = :table_location
                            WHERE `id` = :id';

                $stmt = $db->prepare($query);

                $stmt->bindParam(':guest_name', $_GET['name']);
                $stmt->bindParam(':guest_email', $_GET['email']);
                $stmt->bindParam(':guest_tel', $_GET['tel']);
                $stmt->bindParam(':reservation_date', $_GET['reservationDate']);
                $stmt->bindParam(':reservation_time', $_GET['reservationTime']);
                $stmt->bindParam(':guests', $_GET['guestsNumber'], PDO::PARAM_INT);
                $stmt->bindParam(':table_location', $_GET['tableLocation']);
                $stmt->bindParam(':id', $_GET['id']);

            } else {
                echo "<h1>inserting</h1>";
                $query = 'INSERT
                          INTO reservation (guest_name, guest_email, guest_tel, reservation_date, reservation_time, guests, table_location)
                          VALUES (:guest_name, :guest_email, :guest_tel, :reservation_date, :reservation_time, :guests, :table_location)';

                $stmt = $db->prepare($query);

                $stmt->bindParam(':guest_name', $_GET['name']);
                $stmt->bindParam(':guest_email', $_GET['email']);
                $stmt->bindParam(':guest_tel', $_GET['tel']);
                $stmt->bindParam(':reservation_date', $_GET['reservationDate']);
                $stmt->bindParam(':reservation_time', $_GET['reservationTime']);
                $stmt->bindParam(':guests', $_GET['guestsNumber'], PDO::PARAM_INT);
                $stmt->bindParam(':table_location', $_GET['tableLocation']);
            }

            $stmt->execute();

            $errorInfo = $stmt->errorInfo();

            if(isset($errorInfo[2])){
                $error = $errorInfo[2];
                echo $error;
            } else {
                echo "Reservations data added to de database.\n";

            }

            // EMAIL

            $to = 'Brahian E. Soto M. <brahiansoto@use.startmail.com>, elias@use.startmail.com';
            $subjetct = 'Testing the reservation System';
            $body = 'This is the body of the email message';
            $headers = "From: reservation_system\r\n";
            $headers .= "Content-Type: text/plain; Charset=utf-8\r\n";
            $headers .= "Cc: brahiansoto@hotmail.com";

            $success = mail($to, $subjetct, $body, $headers, '-fbrahiansoto@use.startmail.com');
            if ($success){
                echo "Email sent.\n";
            } else {
                echo "Failed to then the email.";
            }

        } catch(Exception $e) {
            $error = $e->getMessage();

        }

    } else {

        echo "<h1>We ain't got shit!</h1>";
    }
    
?>



