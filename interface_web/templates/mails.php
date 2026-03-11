<main class="container my-4">

    <!-- Titre et rôle de la page -->
    <h1 class="mb-4 fw-bold text-dark text-center">Boîte mail</h1>
    <p class="text-muted mb-4 text-center">Consultez vos mails, envoyez-en de nouveaux et gérez votre messagerie.</p>

    <!-- Card pour envoyer un mail -->
    <div class="row mb-4">

        <div class="col-12">

            <!-- Formulaire d'envoi -->
            <div class="card shadow-sm">

                <div class="card-header bg-light text-dark fw-bold text-center">Nouveau mail</div>

                <div class="card-body">

                    <form method="POST">

                        <!-- Champ pour l'email du destinataire -->
                        <input type="email" name="destinataire" class="form-control mb-3" placeholder="Destinataire" required>

                        <!-- Champ pour le sujet / l'objet du mail -->
                        <input type="text" name="sujet" class="form-control mb-3" placeholder="Sujet" required>

                        <!-- Champ pour le message du mail -->
                        <textarea name="message" class="form-control mb-3" rows="4" placeholder="Votre message..." required></textarea>

                        <!-- Bouton pour envoyer le mail -->
                        <button class="btn btn-dark w-100">Envoyer</button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4 mb-4">

        <!-- Card pour lister tous les mails envoyés -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">

                <div class="card-header bg-light fw-bold text-center">Mails envoyés</div>
                <div class="card-body">

                    <div class="list-group list-group-flush">

                        <?php foreach($mails_envoyes as $mail): ?>

                            <!-- Lorsque l'on clique sur le mail, on nous renvoie vers une page avec le mail en entier -->
                            <a href="mail.php?id=<?= $mail['id'] ?>" class="list-group-item list-group-item-action py-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>

                                        <!-- Sujet du mail -->
                                        <h6 class="mb-1 text-dark"><?= htmlspecialchars($mail['sujet']) ?></h6>

                                        <!-- Destinataire + Date d'envoi du mail -->
                                        <div class="text-muted small">
                                            À <strong><?= htmlspecialchars($mail['destinataire']) ?></strong>
                                            <?= $mail['date'] ? "le " . date('d/m/Y H:i', strtotime($mail['date'])) : '' ?>
                                        </div>

                                    </div>

                                </div>

                            </a>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        </div>

        <!-- Card pour lister les mails reçus -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">

                <div class="card-header bg-light fw-bold text-center">Mails reçus</div>
                <div class="card-body">

                    <div class="list-group list-group-flush">

                        <?php foreach($mails_recus as $mail): ?>

                            <!-- Lorsque l'on clique sur le mail, on nous renvoie vers une page avec le mail en entier -->
                            <a href="mail.php?id=<?= $mail['id'] ?>" class="list-group-item list-group-item-action py-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>

                                        <!-- Badge si non lu -->
                                        <?php if(!$mail['lu']): ?>
                                            <span class="badge bg-success mb-1">Nouveau</span>
                                        <?php endif; ?>

                                        <!-- Sujet du mail -->
                                        <h6 class="mb-1 text-dark"><?= htmlspecialchars($mail['sujet']) ?></h6>

                                        <div class="text-muted small">
                                            De <strong><?= htmlspecialchars($mail['expediteur']) ?></strong>
                                            <?= $mail['date'] ? "le " . date('d/m/Y H:i', strtotime($mail['date'])) : '' ?>
                                        </div>

                                    </div>

                                    <!-- Actions -->
                                    <div class="d-flex justify-content-end gap-2">

                                        <form method="POST">
                                            <input type="hidden" name="toggle_lu" value="<?= $mail['id'] ?>">
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <?= $mail['lu'] ? 'Marquer non lu' : 'Marquer lu' ?>
                                            </button>
                                        </form>

                                        <form method="POST">
                                            <input type="hidden" name="supprimer" value="<?= $mail['id'] ?>">
                                            <button class="btn btn-sm btn-outline-danger">
                                                Supprimer
                                            </button>
                                        </form>

                                    </div>

                                </div>

                            </a>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>