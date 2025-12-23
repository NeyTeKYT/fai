<main class="container my-5">

    <!-- Titre et slogan de la page -->
    <h1 class="mb-4 fw-bold text-dark text-center">Connectez-vous</h1>
    <p class="text-muted mb-4 text-center">Connectez-vous pour administrer votre box Internet.</p>

    <div class="d-flex justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">

            <div class="card shadow-sm">

                <!-- Titre de la card -->
                <div class="card-header bg-light text-dark fw-bold text-center">Formulaire de connexion</div>

                <div class="card-body">

                    <form method="POST">

                        <!-- Champ pour le nom d'utilisateur -->
                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold">Nom d'utilisateur</label>
                            <input type="text" name="username" class="form-control" id="username" placeholder="Nom d'utilisateur" required>
                        </div>

                        <!-- Champ pour le mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Mot de passe</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Mot de passe" required>
                        </div>

                        <!-- Bouton pour se connecter -->
                        <button type="submit" class="btn btn-dark w-100 mt-3">Se connecter</button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</main>
