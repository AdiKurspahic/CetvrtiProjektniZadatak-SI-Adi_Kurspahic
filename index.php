<?php
require_once "read.php";

$msg = isset($_GET["msg"]) ? $_GET["msg"] : "";
$error = isset($_GET["error"]) ? $_GET["error"] : "";

$editUser = null;
$edit_id = isset($_GET["edit_id"]) ? (int)$_GET["edit_id"] : 0;
if ($edit_id > 0) {
    foreach ($users as $u) {
        if ((int)$u["id"] === $edit_id) {
            $editUser = $u;
            break;
        }
    }
}

function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>php-mysql-backend</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container py-4">

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 m-0">Users CRUD</h1>
    <span class="badge bg-secondary">php-mysql-backend</span>
  </div>

  <?php if ($msg === "created"): ?>
    <div class="alert alert-success">Korisnik je uspješno dodan.</div>
  <?php elseif ($msg === "updated"): ?>
    <div class="alert alert-success">Korisnik je uspješno ažuriran.</div>
  <?php elseif ($msg === "deleted"): ?>
    <div class="alert alert-warning">Korisnik je obrisan.</div>
  <?php endif; ?>

  <?php if ($error !== ""): ?>
    <div class="alert alert-danger"><?php echo e($error); ?></div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-12 col-lg-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h2 class="h5">Dodaj korisnika</h2>
          <form id="createForm" action="create.php" method="post" novalidate>
            <div class="mb-3">
              <label class="form-label">Ime</label>
              <input type="text" class="form-control" name="ime" id="ime" required>
              <div class="invalid-feedback">Ime je obavezno.</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Prezime</label>
              <input type="text" class="form-control" name="prezime" id="prezime" required>
              <div class="invalid-feedback">Prezime je obavezno.</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" id="email" required>
              <div class="invalid-feedback">Email je obavezan.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sačuvaj</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h2 class="h5 m-0">Lista korisnika</h2>
            <input class="form-control form-control-sm w-auto" id="searchInput" placeholder="Pretraga...">
          </div>

          <div class="table-responsive">
            <table class="table table-striped align-middle" id="usersTable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Ime</th>
                  <th>Prezime</th>
                  <th>Email</th>
                  <th>Kreirano</th>
                  <th class="text-end">Akcije</th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($users) === 0): ?>
                  <tr><td colspan="6" class="text-center text-muted py-4">Nema korisnika.</td></tr>
                <?php else: ?>
                  <?php foreach ($users as $u): ?>
                    <tr>
                      <td><?php echo e($u["id"]); ?></td>
                      <td><?php echo e($u["ime"]); ?></td>
                      <td><?php echo e($u["prezime"]); ?></td>
                      <td><?php echo e($u["email"]); ?></td>
                      <td><?php echo e($u["created_at"]); ?></td>
                      <td class="text-end">
                        <a class="btn btn-sm btn-outline-secondary" href="index.php?edit_id=<?php echo e($u["id"]); ?>#edit">Edit</a>
                        <a class="btn btn-sm btn-outline-danger btn-delete" href="delete.php?id=<?php echo e($u["id"]); ?>" data-user="<?php echo e($u["ime"] . " " . $u["prezime"]); ?>">Delete</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="mt-4" id="edit">
    <div class="card shadow-sm">
      <div class="card-body">
        <h2 class="h5">Uredi korisnika</h2>

        <?php if ($editUser === null): ?>
          <p class="text-muted m-0">Klikni <strong>Edit</strong> na korisniku iz tabele da se forma popuni.</p>
        <?php else: ?>
          <form id="updateForm" action="update.php" method="post" class="row g-3" novalidate>
            <input type="hidden" name="id" value="<?php echo e($editUser["id"]); ?>">
            <div class="col-12 col-md-4">
              <label class="form-label">Ime</label>
              <input type="text" class="form-control" name="ime" id="u_ime" value="<?php echo e($editUser["ime"]); ?>" required>
              <div class="invalid-feedback">Ime je obavezno.</div>
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label">Prezime</label>
              <input type="text" class="form-control" name="prezime" id="u_prezime" value="<?php echo e($editUser["prezime"]); ?>" required>
              <div class="invalid-feedback">Prezime je obavezno.</div>
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" id="u_email" value="<?php echo e($editUser["email"]); ?>" required>
              <div class="invalid-feedback">Email je obavezan.</div>
            </div>
            <div class="col-12 d-flex gap-2">
              <button type="submit" class="btn btn-success">Sačuvaj izmjene</button>
              <a href="index.php" class="btn btn-outline-secondary">Otkaži</a>
            </div>
          </form>
        <?php endif; ?>

      </div>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="assets/script.js"></script>
</body>
</html>
