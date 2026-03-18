<?php if(!empty($resultats_ia)): ?>

    <div class="d-flex justify-content-center mt-4">
        <div class="col-12 col-lg-8">

            <div class="card shadow-sm">

                <?php if(isset($_POST['titre_ia'])): ?>
                    <div class="card-header fw-bold text-center">Résultats de l'IA pour votre titre : <?= htmlspecialchars($_POST['titre']) ?></div>
                <?php elseif(isset($_POST['message_ia'])): ?>
                    <div class="card-header fw-bold text-center">Résultats de l'IA pour votre message : <?= htmlspecialchars($_POST['message']) ?></div>
                <?php endif; ?>

                <div class="list-group list-group-flush">

                    <?php foreach($resultats_ia as $resultat): ?>

                        <a href="discussion.php?id=<?= $resultat['id'] ?>" class="list-group-item list-group-item-action py-3">

                            <div class="row align-items-start">

                                <div class="col-12 col-md-6">

                                    <?php if($type_ia === 'discussion'): ?>

                                        <!-- Titre de la discussion -->
                                        <h5 class="mb-1 text-dark"><?= htmlspecialchars($resultat['title']) ?></h5>

                                        <div class="text-muted">
                                            Lancée par <strong><?= htmlspecialchars($resultat['creator']) ?></strong>
                                        </div>

                                    <?php elseif($type_ia === 'message'): ?>

                                        <h5 class="mb-1 text-dark">
                                            Message trouvé
                                        </h5>

                                        <div class="text-muted">
                                            <?= htmlspecialchars($resultat['message']) ?>
                                        </div>

                                    <?php endif; ?>

                                </div>

                                <!-- COLONNE DROITE -->
                                <div class="col-12 col-md-6 mt-3 mt-md-0">

                                    <?php if($type_ia === 'discussion'): ?>

                                        <?php if(!empty($resultat['date_dernier_message']) &&
                                            strtotime($resultat['date_dernier_message']) > strtotime('-1 day')): ?>
                                            <span class="badge bg-success mb-1">Nouveau</span>
                                        <?php endif; ?>

                                        <div class="fw-bold text-muted small mt-1">
                                            Dernier message :
                                        </div>

                                        <div class="text-muted text-truncate">
                                            <?= htmlspecialchars($resultat['dernier_message'] ?? '') ?>
                                        </div>

                                    <?php elseif($type_ia === 'message'): ?>

                                        <div class="fw-bold text-muted small mt-1">
                                            Contexte :
                                        </div>

                                        <div class="text-muted text-truncate">
                                            <?= htmlspecialchars($resultat['message']) ?>
                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </a>

                    <?php endforeach; ?>

                </div>
            </div>

        </div>
    </div>

<?php endif; ?>