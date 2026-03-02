<?php
require_once "config.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
    $ime = isset($_POST["ime"]) ? trim($_POST["ime"]) : "";
    $prezime = isset($_POST["prezime"]) ? trim($_POST["prezime"]) : "";
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";

    if ($id <= 0) {
        $errors[] = "Neispravan ID korisnika.";
    }
    if ($ime === "" || $prezime === "" || $email === "") {
        $errors[] = "Sva polja su obavezna.";
    }

    if (empty($errors)) {

        $stmt = $conn->prepare("UPDATE users SET ime = ?, prezime = ?, email = ? WHERE id = ?");
        if (!$stmt) {
            $errors[] = "Greška pri pripremi upita.";
        } else {
            $stmt->bind_param("sssi", $ime, $prezime, $email, $id);
            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                header("Location: index.php?msg=updated");
                exit;
            } else {
                $errors[] = "Neuspješno ažuriranje. Provjeri da li email već postoji.";
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
