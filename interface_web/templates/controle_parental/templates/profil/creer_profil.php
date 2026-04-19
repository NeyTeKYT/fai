<div class="col-12 col-lg-6">
    <div class="card shadow-sm h-100">

        <div class="card-header bg-light fw-bold text-center">Création d'un profil</div>
        <div class="card-body">

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Âge</label>
                    <input type="number" name="age" id="age-input" class="form-control" min="1" max="17" required>
                </div>

                <div class="mt-3">
                    <label class="form-label">Appareil lié au profil</label>
                    <input type="text" name="ip" class="form-control" placeholder="Adresse IPv4" pattern="^(\d{1,3}\.){3}\d{1,3}$" required>
                </div>

                <?php 

                    // Vérifie s'il existe au moins un planning dans la BDD pour proposer l'IA
                    $stmt_check = $pdo->query("SELECT COUNT(*) FROM planning_controle_parental");
                    $nb_plannings = $stmt_check->fetchColumn();
                    
                ?>

                <?php if($nb_plannings > 0) : ?>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="ia_planning" id="ia-planning" value="1">
                        <label class="form-check-label" for="ia-planning">Utiliser l'IA pour créer une première version du planning basée sur les profils d'âge similaire</label>
                    </div>
                <?php endif; ?>

                <button type="submit" name="add_profil" class="btn btn-dark w-100 mt-3">Créer le profil</button>

            </form>

        </div>

    </div>
</div>