<?php

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    $sql = "INSERT INTO epos_zadatak.adminreg (first_name, last_name, password, email) VALUES (?, ?, ?, ?)";


    $run = $conn->prepare($sql);

    if (!$run) {
        die("Greška pri pripremi upita: " . mysqli_error($conn));
    }

    $run->bind_param("ssss", $first_name, $last_name, $password, $email);

    if ($run->execute()) {
        // Podaci su uspešno uneti u bazu.
        $userData = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'password' => $password,
            'email' => $email
        );

        $jsonData = json_encode($userData);
        $jsonFilePath = "C:\\Users\\Admin\\Desktop\\jsonEpos.txt";

        $file = fopen($jsonFilePath, 'a+');
        if ($file) {
            if (fwrite($file, $jsonData)) {
                fclose($file);
                header("Location: admin.php");
            } else {
                fclose($file);
            }
        } else {
            echo "Greška pri otvaranju fajla";
        }
    } else {
        $_SESSION['error'] = "Greška pri unosu u bazu: " . $conn->error;
        header("Location: index.php");
        exit();
    }


}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign-Up</title>
</head>

<body>

    <?php

    if (isset($_SESSION['error'])) {
        echo $_SESSION['error'] . "<br/>";
        unset($_SESSION['error']);
    }

    ?>

    <form action="" method="post">
        First name: <input type="first_name" name="first_name"><br>
        Last name: <input type="last_name" name="last_name"><br>
        Password: <input type="password" name="password"><br>
        Email: <input type="email" name="email"><br>
        <input type="submit" value="Login">
    </form>
</body>

</html>