<!-- Formulaire pour créer une discussion -->
<?php if($role_utilisateur !== 'technicien'): ?>
    <div class="d-flex justify-content-center">
        <div class="col-12 col-lg-6">

            <div class="card shadow-sm">

                <div class="card-header bg-light text-dark fw-bold text-center">Écrire un message</div>

                <div class="card-body">

                    <!-- Formulaire qui une fois envoyé renvoie la page de la discussion -->
                    <form method="POST">  

                        <div class="card-body">

                            <!-- Titre de la discussion -->
                            <div class="d-flex align-items-center">

                                <!-- Bouton pour lancer l'algorithme pour chercher des titres similaires dans la BDD -->
                                <input type="text" name="titre" class="form-control mb-3 me-3" placeholder="Titre de la discussion">
                                <button type="submit" name="titre_ia" class="btn btn-sm btn-outline-info">IA</button>

                            </div>

                            <!-- Message -->
                            <div class="d-flex align-items-center">
                                    
                                <!-- Bouton pour lancer l'algorithme pour chercher des titres similaires dans la BDD -->
                                <textarea name="message" class="form-control mb-3 me-3" rows="4" placeholder="Message"></textarea>
                                <button type="submit" name="message_ia" class="btn btn-sm btn-outline-info">IA</button>

                            </div>

                            <!-- Bouton pour soumettre -->
                            <button type="submit" name="creer_discussion" class="btn btn-dark w-100">Envoyer</button>

                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>