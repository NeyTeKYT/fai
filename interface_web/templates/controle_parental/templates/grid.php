<?php if($profil_selectionne) : ?>

    <div class="card shadow-sm mb-4 mx-4">

        <!-- Texte personnalisé en fonction du profil pour indiquer de quelle grille il s'agit -->
        <div class="card-header bg-light fw-bold text-center">
            Planning du contrôle parental : <?= htmlspecialchars($profil_selectionne['nom']) ?>
            <span class="text-muted fw-normal fs-6">(<?= htmlspecialchars($profil_selectionne['adresse_ipv4']) ?>)</span>
        </div>

        <div class="card-body">

            <!-- Règle de la grille de contrôle parental -->
            <p class="text-muted mb-3 text-center">
                Cliquez sur une case pour bloquer <span class="badge bg-danger">rouge</span> ou autoriser 
                <span class="badge bg-success">vert</span> l'accès à Internet pour cette heure.
            </p>

            <!-- Grille scrollable horizontalement sur petit écran -->
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle" id="grille-controle" style="min-width: 900px;">

                    <thead class="table-dark">
                        <tr>
                            <th style="width: 80px;">Jour</th>
                            <?php for($h = 0; $h < 24; $h++) : ?>
                                <th style="font-size: 0.7rem; padding: 4px;"><?= str_pad($h, 2, '0', STR_PAD_LEFT) ?>h</th>
                            <?php endfor; ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                            $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                            foreach($jours as $index_jour => $jour) : 
                        ?>
                            <tr>
                                <td class="fw-bold" style="font-size: 0.8rem;"><?= $jour ?></td>
                                <?php for($h = 0; $h < 24; $h++) : ?>
                                    <?php $bloque = $grille[$index_jour][$h] ?? 0; ?>
                                        <td class="cellule-grille <?= $bloque ? 'bg-danger' : 'bg-success' ?>"
                                            data-jour="<?= $index_jour ?>"
                                            data-heure="<?= $h ?>"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="<?= $jour ?> <?= str_pad($h, 2, '0', STR_PAD_LEFT) ?>h"
                                            style="cursor: pointer; height: 36px; transition: background-color 0.15s;">
                                        </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

            <!-- Bouton pour appliquer les changements -->
            <div class="text-center mt-3">
                <button class="btn btn-dark px-4" id="btn-sauvegarder-grille">Sauvegarder le planning</button>
                <p class="text-muted mt-2 mb-0" id="msg-sauvegarde" style="display:none;"></p>
            </div>

        </div>

    </div>

    <script>

        // État de la grille après récupération dans la BDD de la configuration actuelle
        const grille = <?= json_encode($grille) ?>;
        const idProfil = <?= $profil_selectionne['id'] ?>;

        // Bascule le statut d'une case au clic (vert -> rouge / rouge -> vert)
        document.querySelectorAll('.cellule-grille').forEach(cellule => {
            cellule.addEventListener('click', function() {
                const jour  = parseInt(this.dataset.jour);
                const heure = parseInt(this.dataset.heure);

                grille[jour][heure] = grille[jour][heure] ? 0 : 1;  // Bascule 0 -> 1 ou 1 -> 0

                // Met à jour la couleur
                if(grille[jour][heure]) {
                    this.classList.remove('bg-success');
                    this.classList.add('bg-danger');
                } 
                else {
                    this.classList.remove('bg-danger');
                    this.classList.add('bg-success');
                }
            });
        });

        // Sauvegarde l'état de la grille après l'appuie sur le bouton de sauvegarde
        document.getElementById('btn-sauvegarder-grille').addEventListener('click', function() {

            const formData = new FormData();
            formData.append('save_grille', '1');
            formData.append('id_profil', idProfil);
            formData.append('grille_json', JSON.stringify(grille));

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                const msg = document.getElementById('msg-sauvegarde');
                msg.style.display = 'block';
                if(data.success) window.location.reload();
                else {
                    const msg = document.getElementById('msg-sauvegarde');
                    msg.style.display = 'block';
                    msg.className = 'text-danger mt-2 mb-0';
                    msg.textContent = 'Erreur lors de la sauvegarde.';
                }
                setTimeout(() => msg.style.display = 'none', 3000);
            });

        });

        // Tooltips Bootstrap pour indiquer le jour et l'heure lors du hover d'une case
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el, { trigger: 'hover' });
        });

    </script>

<?php endif; ?>