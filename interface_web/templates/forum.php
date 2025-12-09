<main class="container my-4">

    <!-- Titre et rôle de la page -->
	<!-- La version par défaut est la version "débutante" donc j'essaye d'utiliser des termes 
	 simples, différents pour une personne qui ne serait à l'aise avec les termes techniques -->
    <h1 class="mb-4 fw-bold text-dark text-center">Aide</h1>
    <p class="text-muted mb-4 text-center">Discutez avec un technicien pour régler un problème ou poser une question.</p>

    <div class="d-flex justify-content-center mb-4">
        <div class="col-12 col-lg-6">

            <!-- Affichage des 10 derniers messages -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light text-dark fw-bold text-center">Discussion</div>

                <div class="card-body p-3" id="messages-box" style="max-height: 400px; overflow-y: auto;">

                    <?php

                        // Récupération des 10 derniers messages 
                        $query = $pdo->prepare("
                            SELECT forum.message, forum.date, user.username
                            FROM forum
                            INNER JOIN user ON forum.id_user = user.id
                            ORDER BY forum.id_message DESC
                            LIMIT 10
                        ");

                        // Lance la requête
                        $query->execute();

                        // Récupération des messages
                        $messages = $query->fetchAll();

                        foreach($messages as $message):

                    ?>

                    <!-- Format pour afficher les messages -->
                    <div class="mb-3 pb-2 border-bottom">

                        <!-- Affichage de l'utilisateur qui a publié le message -->
                        <strong><?= htmlspecialchars($message['username']) ?></strong>

                        <!-- Affichage de la date à laquelle a été publié le message -->
                        <div class="text-secondary small"><?= htmlspecialchars($message['date']) ?></div>

                        <!-- Affichage du message en gardant le format de l'utilisateur -->
                        <div><?= nl2br(htmlspecialchars($message['message'])) ?></div>

                    </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire pour envoyer un message -->
    <div class="d-flex justify-content-center">
        <div class="col-12 col-lg-6">

            <div class="card shadow-sm">

                <div class="card-header bg-light text-dark fw-bold text-center">Écrire un message</div>

                <div class="card-body">
                    <form method="POST" action="">

                        <div class="mb-3 text-center">
                            <label for="message" class="form-label fw-bold">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="3" required style="resize: none;"></textarea>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 mt-2">Envoyer</button>

                    </form>
                </div>

            </div>
        </div>
    </div>

</main>
