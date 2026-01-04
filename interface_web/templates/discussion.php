<main class="container my-4">

    <!-- Titre et rôle de la page -->
    <h1 class="mb-4 fw-bold text-dark text-center"><?= htmlspecialchars($discussion['title']) ?></h1>
    <p class="text-muted mb-4 text-center">Discussion lancée par <strong><?= htmlspecialchars($discussion['createur']) ?></strong></p>

    <!-- Affichage du tchat -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <!-- Cas où aucun message n'a été récupéré pour cette discussion -->
            <?php if(empty($messages)): ?>
                <p class="text-muted text-center">Aucun message pour le moment.</p>
            <?php endif; ?>

            <?php foreach($messages as $message): ?>

                <!-- Bool pour savoir si l'utilisateur est celui qui a envoyé le message -->
                <?php $is_me = ($message['user_id'] == $_SESSION['id']); ?>

                <!-- Comme dans un tchat traditionnel, si c'est l'utilisateur connecté qui a envoyé le message alors on met le message en bas à droite
                 et si ce n'est pas nous alors on le met en bas à gauche -->
                <div class="d-flex mb-3 <?= $is_me ? 'justify-content-end' : 'justify-content-start' ?>">
                    <div class="p-4 rounded <?= $is_me ? 'bg-dark text-white' : 'bg-light' ?>" style="width: 100%; max-width: 85%;">
                        
                        <div class="fw-bold mb-1 d-flex align-items-center gap-2">
                            
                            <!-- Affichage du nom d'utilisateur de l'utilisateur qui a envoyé ce message -->
                            <?= htmlspecialchars($message['username']) ?>

                            <!-- Si celui qui a envoyé le message a le rôle de technicien alors on lui attribue un badge de vérification de sa réponse -->
                            <?php if($message['role'] === 'technicien'): ?>
                                <span class="badge bg-success">Technicien</span>
                            <?php endif; ?>

                        </div>

                        <!-- Affichage du message tout en conservant le format du message -->
                        <div class="<?= $message['message'] === '[message supprimé]' ? 'fst-italic text-white' : '' ?>">
                            <?= nl2br(htmlspecialchars($message['message'])) ?>
                        </div>

                        <!-- Affichage de la date de publication du message -->
                        <div class="text-end small mt-1 <?= ($message['user_id'] == $_SESSION['id']) ? 'text-white' : 'text-muted' ?>">
                            <?= date('d/m/Y', strtotime($message['date'])) ?>
                        </div>

                        <!-- Bouton pour supprimer le message --> 
                        <div>
                            <?php if ($is_me && $message['message'] !== '[message supprimé]'): ?>
                                <form method="POST" class="ms-auto">
                                    <input type="hidden" name="delete_message_id" value="<?= $message['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce message ?');">Supprimer</button>
                                </form>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <!-- Formulaire d'envoi d'un message-->
    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST">

                <textarea name="message" class="form-control mb-3" rows="3" placeholder="Écrire un message..." required></textarea>

                <button class="btn btn-dark w-100">Envoyer</button>

            </form>
            
        </div>
    </div>

</main>
