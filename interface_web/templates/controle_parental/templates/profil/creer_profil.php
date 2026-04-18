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
                    <input type="number" name="age" class="form-control" min="1" max="17" required>
                </div>

                <div class="mt-3">
                    <label class="form-label">Appareil lié au profil</label>
                    <input type="text" name="ip" class="form-control" placeholder="Adresse IPv4" pattern="^(\d{1,3}\.){3}\d{1,3}$" required>
                </div>

                <button type="submit" name="add_profil" class="btn btn-dark w-100 mt-3">Créer le profil</button>

            </form>

        </div>

    </div>
</div>
