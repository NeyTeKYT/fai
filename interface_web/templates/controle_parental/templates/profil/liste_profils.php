<div class="col-12 col-lg-6">
    <div class="card shadow-sm h-100">

        <div class="card-header bg-light fw-bold text-center">Liste des profils</div>
        <div class="card-body">

            <!-- Affichage d'un message centré sur la card pour indiquer à l'utilisateur qu'aucun profil n'existe -->
            <?php if(empty($profils)) : ?>
                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                    <i class="bi bi-person-x fs-1 mb-2"></i>
                    <p class="mb-0">Aucun profil créé pour le moment.</p>
                </div>
            <?php else : ?>

                <!-- Sélection du profil pour afficher la grille correspondante -->
                <form method="POST">
                    <div class="mt-3">
                        <label class="form-label">Profils</label>
                        <select name="profil" class="form-select" id="select-profil">
                            <?php foreach($profils as $profil) : ?>
                                <option value="<?= $profil['id'] ?>"
                                    <?= (isset($profil_selectionne) && $profil_selectionne['id'] == $profil['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($profil['nom']) ?> <-> <?= htmlspecialchars($profil['adresse_ipv4']) ?> (<?= $profil['age'] ?> ans)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="choose_profil" class="btn btn-dark w-100 mt-3">Choisir ce profil</button>
                </form>

                <hr class="my-3">   <!-- Transition avec le formulaire pour modifier / supprimer un profil -->

                <!-- Modification du profil sélectionné dans le select -->
                <div id="zone-modification">
                    <?php foreach($profils as $profil) : ?>
                        <form method="POST" class="form-modification d-none" data-id="<?= $profil['id'] ?>">

                            <input type="hidden" name="id_profil" value="<?= $profil['id'] ?>"> <!-- Input caché pour envoyer à la soumission du formulaire l'ID du profil ciblé -->

                            <div class="mb-2">
                                <label class="form-label">Nom</label>
                                <input type="text" name="nom" class="form-control form-control-sm" value="<?= htmlspecialchars($profil['nom']) ?>" required>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Âge</label>
                                <input type="number" name="age" class="form-control form-control-sm" value="<?= $profil['age'] ?>" min="1" max="17" required>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Adresse IPv4</label>
                                <input type="text" name="ip" class="form-control form-control-sm" value="<?= htmlspecialchars($profil['adresse_ipv4']) ?>" pattern="^(\d{1,3}\.){3}\d{1,3}$" required>
                            </div>

                            <div class="d-flex gap-2 mt-2">

                                <button type="submit" name="edit_profil" class="btn btn-dark btn-sm flex-fill">Modifier le profil</button>
                                <button type="submit" name="delete_profil" class="btn btn-danger btn-sm flex-fill" onclick="return confirm('Voulez-vous vraiment supprimer ce profil ?')">Supprimer</button>

                            </div>

                        </form>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

        </div>

    </div>
</div>

<!-- Pourquoi quand je place le code dans mon fichier script.js il ne fonctionne pas ? -->
<!-- J'ai besoin de faire une balise script au sein de ma template pour permettre le fonctionnement de la modification d'un profil -->
<script>

    // Affiche le formulaire de modification correspondant au profil sélectionné dans le select
    const selectProfil = document.getElementById('select-profil');

    function afficherFormModification() {
        // Cache tous les formulaires
        document.querySelectorAll('.form-modification').forEach(f => f.classList.add('d-none'));

        // Affiche celui qui correspond à la sélection
        const id = selectProfil ? selectProfil.value : null;
        if(id) {
            const form = document.querySelector(`.form-modification[data-id="${id}"]`);
            if(form) form.classList.remove('d-none');
        }
    }

    if(selectProfil) {
        selectProfil.addEventListener('change', afficherFormModification);
        // Affiche d'emblée le formulaire du profil actuellement sélectionné
        afficherFormModification();
    }

</script>