<main class="container my-4">

    <h1 class="mb-4 fw-bold text-dark text-center">Paramètres du compte</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center mx-auto" style="max-width: 600px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success text-center mx-auto" style="max-width: 600px;"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body">

            <form method="POST">

                <!-- Nom d'utilisateur -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Nom d'utilisateur</label>
                    <input 
                        type="text" 
                        name="username" 
                        class="form-control" 
                        value="<?= htmlspecialchars($user['username']) ?>" 
                        required>
                </div>

                <!-- Mot de passe -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Nouveau mot de passe</label>
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control"
                        placeholder="Laisser vide pour ne pas changer">
                    <div class="form-text">
                        12 caractères minimum, 1 majuscule, 1 chiffre, 1 caractère spécial.
                    </div>
                </div>

                <!-- Mode de configuration uniquement paramétrable pour les clients -->
                <?php if ($user['role'] !== 'technicien'): ?>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Mode de configuration</label>
                        <select name="mode" class="form-select">
                            <option value="debutant" <?= $user['mode'] === 'debutant' ? 'selected' : '' ?>>
                                Débutant (guidé)
                            </option>
                            <option value="avance" <?= $user['mode'] === 'avance' ? 'selected' : '' ?>>
                                Avancé (options complètes)
                            </option>
                        </select>
                    </div>
                <?php else: ?>
                    <!-- Information technicien -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Mode de configuration</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            value="Non applicable (technicien)" 
                            disabled>
                        <div class="form-text">
                            Le technicien n'a pas pour but d'administrer sa box Internet mais d'administrer les clients du FAI en les aidant sur le forum.
                        </div>
                    </div>
                <?php endif; ?>

                <button class="btn btn-dark w-100">Enregistrer les modifications</button>

            </form>

        </div>
    </div>

</main>
