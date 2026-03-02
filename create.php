<?php

require_once "config.php";

$errors = [];
$ime = $prezime = $email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ime = isset($_POST["ime"]) ? trim($_POST["ime"]) : "";
    $prezime = isset($_POST["prezime"]) ? trim($_POST["prezime"]) : "";
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";

    if ($ime === "" || $prezime === "" || $email === "") {
        $errors[] = "Sva polja su obavezna.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO users (ime, prezime, email) VALUES (?, ?, ?)");
        if (!$stmt) {
            $errors[] = "Greška pri pripremi upita.";
        } else {
            $stmt->bind_param("sss", $ime, $prezime, $email);

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                header("Location: index.php?msg=created");
                exit;
            } else {
                $errors[] = "Neuspješno dodavanje. Provjeri da li email već postoji.";
                $stmt->close();
            }
        }
    }
}

$conn->close();
$err = urlencode(implode(" ", $errors));
header("Location: index.php?error=" . $err);
exit;
?>
