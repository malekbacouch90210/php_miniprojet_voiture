<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=gestion_voiture;charset=utf8', 'root','' );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $voitures_disponibles = [];
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date_debut'], $_POST['date_fin'])) {
    $date_debut = $_POST['date_debut'];
    $date_fin =$_POST['date_fin'];
    $sql = "
        SELECT * FROM voitures 
        WHERE id NOT IN (
            SELECT voiture_id FROM reservations 
            WHERE (date_debut <= :date_fin AND date_fin >= :date_debut)
        ) AND disponibilite = 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':date_debut', $date_debut);
    $stmt->bindParam(':date_fin', $date_fin);
    $stmt->execute();
    $voitures_disponibles = $stmt->fetchAll();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['voiture_id'], $_POST['date_debut'], $_POST['date_fin'])) {
    $voiture_id = $_POST['voiture_id'];
    $date_debut = $_POST['date_debut'];
    $date_fin =$_POST['date_fin'];
    if ($date_debut <= $date_fin) {
        $sql = "
            INSERT INTO reservations (voiture_id, client_id, date_debut, date_fin) 
            VALUES (:voiture_id, 1, :date_debut, :date_fin)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':voiture_id', $voiture_id);
        $stmt->bindParam(':date_debut', $date_debut);
        $stmt->bindParam(':date_fin', $date_fin);
        $stmt->execute();
        $message = "Réservation réussie !";
    } else {
        $message = "La date de début ne peut pas être après la date de fin.";
    }
}
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche et Réservation</title>
    <link rel="stylesheet" href="client_styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="hero-section">
        <h1>Bienvenue à notre site  de voitures "7ilm karhibti 🚘"</h1>
        <p>Trouvez et réservez facilement une voiture pour vos besoins</p>
    </div>

    <div class="container">
        <form method="POST" class="mb-5">
            <div class="row">
                <div class="col-md-5">
                    <label for="date_debut" class="form-label">Date de début</label>
                    <input type="date" class="form-control" id="date_debut" name="date_debut" required>
                </div>
                <div class="col-md-5">
                    <label for="date_fin" class="form-label">Date de fin</label>
                    <input type="date" class="form-control" id="date_fin" name="date_fin" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Rechercher</button>
                </div>
            </div>
        </form>
        <?php
if (!empty($voitures_disponibles)) {
    echo '<h2 class="mb-4 text-center">Voitures Disponibles</h2>';
    echo '<div class="row">';
    foreach ($voitures_disponibles as $voiture) {
        echo '<div class="col-md-4">';
        echo '<div class="card mb-4">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $voiture['marque'] . ' - ' . $voiture['modele'] . '</h5>';
        echo '<p class="card-text">Année : ' . $voiture['annee'] . '</p>';
        echo '<p class="card-text">Immatriculation : ' . $voiture['immatriculation'] . '</p>';
        echo '<form method="POST">';
        echo '<input type="hidden" name="voiture_id" value="' . $voiture['id'] . '">';
        echo '<input type="hidden" name="date_debut" value="' . $_POST['date_debut'] . '">';
        echo '<input type="hidden" name="date_fin" value="' . $_POST['date_fin'] . '">';
        echo '<button type="submit" class="btn btn-success w-100">Réserver</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<p class="text-center text-muted">Aucune voiture disponible pour les dates sélectionnées.</p>';
}

if (isset($message)) {
    echo '<div class="alert alert-success mt-4 text-center" role="alert" id="confirmation-message">';
    echo $message;
    echo '</div>';
}
?>

    </div>
    <footer class="text-center mt-5">
        <p class="text-muted">&copy; 2024 Gestion de Voitures. Tous droits réservés.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="client_script.js"></script>
</body>
</html>


