<main class="container my-4">

    <div class="row mb-4">
        <div class="col-12">

            <div class="card shadow-sm">

                <div class="card-header bg-light text-dark fw-bold text-center">Consultation d'un mail</div>

                <div class="card-body">

                    <form>

                        <!-- Expediteur ou Destinataire en fonction du type de message -->
                        <?php if($type == 'envoye'): ?>
                            <?php if($mail['destinataire']): ?>
                                <label class="fw-bold">Destinataire</label>
                                <input type="text" class="form-control mb-3" value="<?= htmlspecialchars($mail['destinataire']) ?>" readonly>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if($type == 'recu'): ?>
                            <?php if($mail['expediteur']): ?>
                                <label class="fw-bold">Expéditeur</label>
                                <input type="text" class="form-control mb-3" value="<?= htmlspecialchars($mail['expediteur']) ?>" readonly>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Sujet du mail -->
                        <?php if($mail['sujet']): ?>
                            <label class="fw-bold">Sujet</label>
                            <input type="text" class="form-control mb-3" value="<?= htmlspecialchars($mail['sujet']) ?>" readonly>
                        <?php endif; ?>

                        <!-- Date d'envoi du mail -->
                        <?php if($mail['date']): ?>
                            <label class="fw-bold">Date</label>
                            <input type="text" class="form-control mb-3" value="<?= htmlspecialchars($mail['date']) ?>" readonly>
                        <?php endif; ?>    

                        <!-- Contenu du mail -->
                        <?php if($mail['message']): ?>
                            <label class="fw-bold">Message</label>
                            <textarea class="form-control mb-3" rows="8" readonly><?= htmlspecialchars($mail['message']) ?></textarea>
                        <?php endif; ?>

                    </form>

                    <!-- Bouton pour retourner sur la page d'accueil de la messagerie -->
                    <div class="text-center mt-3">
                        <a href="../control/mails.php" class="btn btn-secondary">Retour à la messagerie</a>
                    </div>

                </div>

            </div>

        </div>
    </div>

</main>