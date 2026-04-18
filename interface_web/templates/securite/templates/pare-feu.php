<div class="card shadow-sm h-100">

    <!-- Titre de la card en fonction du mode de configuration -->
    <?php if($_SESSION['mode'] === 'debutant') : ?>
        <div class="card-header bg-light fw-bold text-center">Mise en place de la sécurité de la box</div>
    <?php else : ?>
        <div class="card-header bg-light fw-bold text-center">Mise en place du pare-feu</div>
    <?php endif; ?>

    <div class="card-body">

        <?php if(!$security_enabled) : ?>
            <?php if($_SESSION['mode'] === 'debutant') : ?>
                <p class="text-muted text-center">Une fois la sécurité de la box activée, les appareils connectés pourront accéder à Internet en toute sécurité.</p>
                <form method="POST">
                    <button type="submit" name="enable_security" class="btn btn-dark w-100 mb-3">Activer la sécurité</button>
                </form>
                <?php else : ?>
                    <p class="text-muted text-center">Une fois le pare-feu activé, une politique de sécurité sera mise en place pour autoriser les hôtes à accéder à Internet tout en n'autorisant les réponses venues de l'extérieur que pour les requêtes venues du LAN.</p>
                    <form method="POST">
                        <button type="submit" name="enable_security" class="btn btn-dark w-100 mb-3">Activer le pare-feu</button>
                    </form>
                <?php endif; ?>
            <?php else : ?>
                <?php if($_SESSION['mode'] === 'debutant') : ?>
                    <p class="text-muted text-center">Une fois la sécurité de la box désactivée, les appareils connectés ne pourront plus accéder à Internet.</p>
                    <form method="POST">
                        <button type="submit" name="disable_security" class="btn btn-warning w-100 mb-3" onclick="return confirm('Désactiver la sécurité de la box ?')">Désactiver la sécurité</button>
                    </form>
                <?php else : ?>
                    <p class="text-muted text-center">Une fois le pare-feu désactivé, les appareils ne pourront plus accéder à Internet.</p>
                    <form method="POST">
                        <button type="submit" name="disable_security" class="btn btn-warning w-100 mb-3" onclick="return confirm('Désactiver la sécurité de la box ?')">Désactiver le pare-feu</button>
                    </form>
            <?php endif; ?>
        <?php endif; ?>                

    </div>
</div>