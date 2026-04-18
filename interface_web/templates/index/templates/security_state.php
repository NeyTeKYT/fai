<div class="col-12 col-md-6 col-lg-4">
    <div class="card shadow-sm h-100">

        <div class="card-header bg-light fw-bold text-center">Configuration actuelle</div>

        <div class="card-body">

            <!-- Affichage de la configuration actuelle pour un mode débutant -->
			<?php if($_SESSION['mode'] === 'debutant') : ?>

                <!-- Affichage de l'état du pare-feu -->
                <p><strong>Sécurité de la box :</strong> <?= $security_span ?></p>

                <!-- Affichage de l'état de la connexion à Internet -->
                <p><strong>Accès Internet pour les appareils :</strong> <?= $internet_span ?></p>

            <!-- Affichage de la configuration actuelle pour un mode avancé -->
			<?php else : ?>

                <!-- Affichage de l'état du pare-feu -->
                <p><strong>État du pare-feu :</strong> <?= $security_span ?></p>

                <!-- Affichage de l'état de la connexion à Internet -->
                <p><strong>Accès Internet pour les appareils :</strong> <?= $internet_span ?></p>

            <?php endif; ?>

        </div>
        
    </div>
</div>