<?php if(count($discussions) > 0): ?>
    <div class="d-flex justify-content-center mt-4">
        <div class="col-12 col-lg-8">

            <div class="card shadow-sm">
                <div class="card-header fw-bold text-center">Discussions</div>

                <div class="list-group list-group-flush">

                    <?php foreach($discussions as $discussion): ?>

                        <a href="discussion.php?id=<?= $discussion['id'] ?>" class="list-group-item list-group-item-action py-3">

                            <div class="row align-items-start">

                                <div class="col-12 col-md-6">

                                    <!-- Titre de la discussion -->
                                    <h5 class="mb-1 text-dark"><?= htmlspecialchars($discussion['title']) ?></h5>

                                    <!-- Créateur et date de création -->
                                    <div class="text-muted">
                                        Lancée par <strong><?= htmlspecialchars($discussion['createur']) ?></strong>
                                        <?= $discussion['date_creation'] ? "le " . date('d/m/Y', strtotime($discussion['date_creation'])) : '' ?>
                                    </div>

                                </div>

                                <div class="col-12 col-md-6 mt-3 mt-md-0" style="max-height: 400px; overflow-y: auto;">

                                    <!-- Ajout d'un badge si le dernier message publié a été publié dans les dernières 24 heures -->
                                    <?php 
                                        if(!empty($discussion['date_dernier_message']) &&
                                        strtotime($discussion['date_dernier_message']) > strtotime('-1 day')): 
                                    ?>
                                        <span class="badge bg-success mb-1">Nouveau</span>
                                    <?php endif; ?>

                                    <!-- Indication du dernier message -->
                                    <div class="fw-bold text-muted small mt-1">
                                        Dernier message :
                                    </div>

                                    <!-- Contenu du dernier message -->
                                    <div class="text-muted text-truncate">
                                        <?= htmlspecialchars($discussion['dernier_message']) ?>
                                    </div>

                                    <!-- Utilisateur qui a publié le dernier message et la date de publication -->
                                    <div class="text-muted small mt-1">
                                        Par <strong><?= htmlspecialchars($discussion['dernier_auteur']) ?></strong>
                                        <?= $discussion['date_dernier_message'] ? "le " . date('d/m/Y', strtotime($discussion['date_dernier_message'])): '' ?>
                                    </div>

                                </div>

                            </div>
                        </a>


                    <?php endforeach; ?>

                </div>
            </div>

        </div>

    </div>

<?php endif; ?>
