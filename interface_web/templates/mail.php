<main class="container my-4">

    <div class="row mb-4">
        <div class="col-12">

            <div class="card shadow-sm">

                <div class="card-header bg-light text-dark fw-bold text-center">
                    Consultation d'un mail
                </div>

                <div class="card-body">

                    <form>

                        <!-- Expediteur -->
                        <label class="fw-bold">Expéditeur</label>
                        <input type="text" class="form-control mb-3"
                               value="<?= htmlspecialchars($mail['expediteur']) ?>" readonly>

                        <!-- Sujet -->
                        <label class="fw-bold">Sujet</label>
                        <input type="text" class="form-control mb-3"
                               value="<?= htmlspecialchars($mail['sujet']) ?>" readonly>

                        <!-- Date -->
                        <label class="fw-bold">Date</label>
                        <input type="text" class="form-control mb-3"
                               value="<?= htmlspecialchars($mail['date']) ?>" readonly>

                        <!-- Message -->
                        <label class="fw-bold">Message</label>
                        <textarea class="form-control mb-3" rows="8" readonly><?= htmlspecialchars($mail['message']) ?></textarea>

                    </form>

                    <!-- Bouton retour -->
                    <div class="text-center mt-3">
                        <a href="../control/mails.php" class="btn btn-secondary">
                            Retour à la messagerie
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>

</main>