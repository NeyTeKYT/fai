<!-- Card pour modifier le nom de domaine de la box Internet -->
<div class="col-12 col-lg-6">
    <div class="card shadow-sm h-100">

        <div class="card-header bg-light fw-bold text-center">Nom de domaine de la box Internet</div>

            <div class="card-body">

				<!-- Formulaire pour envoyer le nouveau prénom comme nouvel alias du domaine ceri.com -->
                <form action="formulaire_dns.php" method="POST">

                    <div class="mb-3 text-center">

						<!-- Champ pour le prénom -->
                        <label class="form-label fw-bold">Prénom</label>

                        <div class="d-flex align-items-center flex-wrap justify-content-center">
                            <input type="text" name="first_name" class="form-control text-center w-auto" style="max-width: 250px;" value="<?= $current_first_name; ?>" required>
                            <span class="fw-bold ms-2">.ceri.com</span>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-dark w-100">Modifier le nom de domaine de la box Internet</button>

                </form>

            </div>

        </div>
    </div>
</div>