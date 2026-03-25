<?php if(isset($_POST['titre_ia']) || isset($_POST['message_ia'])): ?>
    
    <?php if(!empty($resultats_ia)): ?>

        <div class="d-flex justify-content-center mt-4">
            <div class="col-12 col-lg-8">

                <div class="card shadow-sm">

                    <?php if(isset($_POST['titre_ia'])): ?>
                        <div class="card-header fw-bold alert alert-info text-center"><?= htmlspecialchars(count($resultats_ia)) ?> similaritées trouvées avec votre titre : <?= htmlspecialchars($_POST['titre']) ?></div>
                    <?php elseif(isset($_POST['message_ia'])): ?>
                        <div class="card-header fw-bold alert alert-info text-center"><?= htmlspecialchars(count($resultats_ia)) ?> similaritées trouvées avec votre message : <?= htmlspecialchars($_POST['message']) ?></div>
                    <?php endif; ?>

                    <div class="list-group list-group-flush">

                        <?php foreach($resultats_ia as $resultat): ?>

                            <a href="discussion.php?id=<?= $resultat['id'] ?>" class="list-group-item list-group-item-action py-3">

                                <div class="row align-items-start">

                                    <div class="col-12 col-md-6">

                                        <?php if($type_ia === 'discussion'): ?>

                                            <!-- Titre de la discussion -->
                                            <h5 class="mb-1 text-dark"><?= htmlspecialchars($resultat['title']) ?></h5>

                                            <!-- Créateur et date de création -->
                                            <div class="text-muted">
                                                Lancée par <strong><?= htmlspecialchars(recuperer_username($resultat['discussion'])) ?></strong>
                                                le <?= date('d/m/Y', strtotime(recuperer_date($resultat['id']))) ?>
                                            </div>

                                        <?php elseif($type_ia === 'message'): ?>

                                            <!-- Message -->
                                            <h5 class="mb-1 text-dark"><?= htmlspecialchars($resultat['message']) ?></h5>

                                            <!-- Auteur et date de publication -->
                                            <div class="text-muted">
                                                Publiée par <strong><?= htmlspecialchars(recuperer_username($resultat['id'])) ?></strong>
                                                le <?= date('d/m/Y', strtotime($resultat['date'])) ?>
                                            </div>

                                        <?php endif; ?>

                                    </div>

                                    <div class="col-12 col-md-6 mt-3 mt-md-0">

                                        <?php 
                                            $interpretation = interpreter_similarite($resultat['score']);
                                        ?>

                                        <span class="badge <?= $interpretation['class'] ?> mb-2"><?= $interpretation['label'] ?></span>

                                        <div class="small text-muted"><?= round($resultat['score'], 2) * 100 ?>% de similarité</div>

                                    </div>

                                </div>

                            </a>

                        <?php endforeach; ?>

                    </div>
                </div>

            </div>
        </div>

    <?php else: ?>

        <div class='alert alert-warning text-center mt-4'>
            Notre IA n'a trouvée aucun résultat suceptible de vous aider, nous vous invitons donc à envoyer votre message sur le forum !
        </div>

    <?php endif; ?>

<?php endif; ?>