<main class="container my-4">

    <!-- Titre et description en fonction du mode de configuration de l'utilisateur -->
    <?php if($_SESSION['mode'] === 'debutant') : ?>
	    <h1 class="mb-4 fw-bold text-dark text-center">Diagnostic de la connexion</h1>
	    <p class="text-muted mb-4 text-center">Cette page permet de diagnostiquer votre connexion entre votre box et notre fournisseur d'accès à Internet.</p>
    <?php else : ?>
        <h1 class="mb-4 fw-bold text-dark text-center">Mesure du débit</h1>
	    <p class="text-muted mb-4 text-center">Cette page permet de mesurer le débit montant et descendant de votre connexion entre votre box et notre fournisseur d'accès à Internet.</p>
    <?php endif; ?>

    <!-- Affichage du bandeau de notification -->
    <?php if(!empty($alerts)) foreach($alerts as $alert) echo $alert; ?>

    <!-- Card pour calculer la vitesse moyenne montante et descendante de la connexion entre la box et le FAI -->
	<div class="row justify-content-center">
		<div class="col-12 col-lg-6">

			<div class="card shadow-sm">

				<div class="card-header bg-light fw-bold text-center">Test de débit</div>

				<div class="card-body text-center">

                    <?php if($_SESSION['mode'] === 'debutant') : ?>
                        <p class="text-muted text-center">Le diagnostic peut prendre plusieurs dizaines de secondes. Merci donc de patienter une fois le calcul lancé.</p>
                    <?php else : ?>
                        <p class="text-muted text-center">La mesure du débit peut prendre plusieurs dizaines de secondes. Merci donc de patienter une fois la mesure lancée.</p>
                    <?php endif; ?>

                    <!-- Bouton qui lorsqu'on clique dessus, passe en "Mesure en cours..." pour éviter de cliquer plusieurs fois dessus et montrer le temps de calcul -->
					<form method="POST" onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerText='Mesure en cours...';">
						<button type="submit" class="btn btn-dark w-100 mb-3">Calculer</button>
					</form>

                </div>
			</div>

		</div>
	</div>

    <!-- Affichage des résultats de la mesure de débit si ils ne sont pas nulls -->
	<?php if($download_speed !== null && $upload_speed !== null) : ?>

        <div class="card shadow-sm mt-4">

            <?php if($_SESSION['mode'] === 'debutant') : ?>
                <div class="card-header bg-light fw-bold text-center">Résultats du diagnostic</div>
            <?php else : ?>
                <div class="card-header bg-light fw-bold text-center">Résultats du test de débit</div>
            <?php endif; ?>

            <div class="card-body text-center">

                <?php if($_SESSION['mode'] === 'debutant') : ?>
                    <p><strong>Moyenne de la vitesse de téléchargement :</strong> <?= round($download_speed, 2); ?> Mbps</p>
                    <p><strong>Moyenne de la vitesse d’envoi :</strong> <?= round($upload_speed, 2); ?> Mbps</p>
                <?php else : ?>
                    <p><strong>Débit descendant moyen :</strong> <?= round($download_speed, 2); ?> Mbps</p>
                    <p><strong>Débit montant moyen :</strong> <?= round($upload_speed, 2); ?> Mbps</p>
                <?php endif; ?>

                <?php if($_SESSION['mode'] === 'debutant' && !empty($verdict_msg)) : ?>
                    <p class="mt-3"><?= $verdict_msg; ?></p>
                <?php endif; ?>

                <?php if($_SESSION['mode'] !== 'debutant' && !empty($usage_msg)) : ?>
                    <p class="text-muted mt-3"><?= $usage_msg; ?></p>
                <?php endif; ?>

            </div>
        </div>
    <?php endif; ?>

    <?php if(!empty($warning_msg)) : ?>
        <div class="alert alert-warning text-center mt-3"><?= $warning_msg; ?></div>
    <?php endif; ?>

</main>
