<?php
$message = "";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=gestion_voiture", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_POST['ajout'])) {
        $marque = $_POST['marque'];
        $modele = $_POST['modele'];
        $annee = $_POST['annee'];
        $immatriculation = $_POST['immatriculation'];
        $stmt = $pdo->prepare("SELECT * FROM voitures WHERE immatriculation = ?");
        $stmt->execute([$immatriculation]);
        if ($stmt->rowCount() > 0) {
            $message = "Erreur : Immatriculation déjà utilisée.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO voitures (marque, modele, annee, immatriculation, disponibilite) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$marque, $modele, $annee, $immatriculation, 1]);
            $message = "Voiture ajoutée avec succès!";
        }
    }
    if (isset($_POST['modifier'])) {
        $id = $_POST['id'];
        $marque = $_POST['marque'];
        $modele = $_POST['modele'];
        $annee = $_POST['annee'];
        $immatriculation = $_POST['immatriculation'];
        $disponibilite = $_POST['disponibilite'];

        $stmt = $pdo->prepare("UPDATE voitures SET marque = ?, modele = ?, annee = ?, immatriculation = ?, disponibilite = ? WHERE id = ?");
        $stmt->execute([$marque, $modele, $annee, $immatriculation, $disponibilite, $id]);
        $message = "Voiture modifiée avec succès!";
    }
    if (isset($_POST['supprimer'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM voitures WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Voiture supprimée avec succès!";
    }
    $stmt = $pdo->query("SELECT * FROM voitures");
    $voitures = $stmt->fetchAll();
} catch (PDOException $e) {
    $message = "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Admin</title>
    <link rel="stylesheet" href="admin_styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
</head>
<body>
<div class="container">
    <h1 class="text-center">Gestion des Voitures</h1>
    <p class="text-center"><?= $message ?></p>
    <form method="post">
        <h3>Ajout d'une Nouvelle Voiture</h3>
        <input type="text" name="marque" placeholder="Marque" class="form-control mb-2" required>
        <input type="text" name="modele" placeholder="Modèle" class="form-control mb-2" required>
        <input type="number" name="annee" placeholder="Année" class="form-control mb-2" required>
        <input type="text" name="immatriculation" placeholder="Immatriculation" class="form-control mb-2" required>
        <button type="submit" name="ajout" class="btn btn-success">Ajouter</button>
    </form>
    <hr>
    <h3>Liste des Voitures</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Année</th>
                <th>Immatriculation</th>
                <th>Disponibilité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($voitures as $voiture) {
            echo '<tr>';
            echo '<td>' . $voiture['id'] . '</td>';
            echo '<td>' . $voiture['marque'] . '</td>';
            echo '<td>' . $voiture['modele'] . '</td>';
            echo '<td>' . $voiture['annee'] . '</td>';
            echo '<td>' . $voiture['immatriculation'] . '</td>';
            echo '<td>' . ($voiture['disponibilite'] == 1 ? 'Disponible' : 'Indisponible') . '</td>';
            echo '<td>';
            echo '<button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modifierModal" 
                data-id="' . $voiture['id'] . '" 
                data-marque="' . $voiture['marque'] . '" 
                data-modele="' . $voiture['modele'] . '" 
                data-annee="' . $voiture['annee'] . '" 
                data-immatriculation="' . $voiture['immatriculation'] . '" 
                data-disponibilite="' . $voiture['disponibilite'] . '">Modifier</button>';
            echo '<form method="post" style="display:inline;">';
            echo '<input type="hidden" name="id" value="' . $voiture['id'] . '">';
            echo '<button type="submit" name="supprimer" class="btn btn-danger" onclick="return confirmDelete();">Supprimer</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
            }
        ?>
        </tbody>
    </table>
</div>
<div class="modal fade" id="modifierModal" tabindex="-1" aria-labelledby="modifierModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modifierModalLabel">Modifier Voiture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" id="modalModifierForm">
          <input type="hidden" name="id" id="modalId">
          <input type="text" name="marque" id="modalMarque" class="form-control mb-2" placeholder="Marque">
          <input type="text" name="modele" id="modalModele" class="form-control mb-2" placeholder="Modèle">
          <input type="number" name="annee" id="modalAnnee" class="form-control mb-2" placeholder="Année">
          <input type="text" name="immatriculation" id="modalImmatriculation" class="form-control mb-2" placeholder="Immatriculation">
          <select name="disponibilite" id="modalDisponibilite" class="form-control mb-2">
            <option value="1">Disponible</option>
            <option value="0">Indisponible</option>
          </select>
          <button type="submit" name="modifier" class="btn btn-warning">Modifier</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="admin_script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

