<main class="container my-4">

    <!-- Titre et description de la page en fonction du mode de configuration de l'utilisateur -->
	<?php if($_SESSION['mode'] === 'debutant') : ?>
		<h1 class="mb-4 fw-bold text-dark text-center">Sécurité de la box</h1>
    	<p class="text-muted mb-4 text-center">Cette page permet de protéger votre réseau et de vous connecter à Internet.</p>
	<?php else : ?>
		<h1 class="mb-4 fw-bold text-dark text-center">Pare-feu</h1>
    	<p class="text-muted mb-4 text-center">Cette page permet d'activer ou désactiver le pare-feu, et propose aussi l'ajout de règles personnalisées.</p>
	<?php endif; ?>

    <!-- Affichage du bandeau de notification -->
	<?php if(!empty($message)) echo $message; ?>

    <div class="row g-4 mb-4">

        <!-- Card pour la configuration actuelle -->
        <div class="col-12 col-lg-6">
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

        <!-- Card pour activer / désactiver la sécurité de la box -->
        <div class="col-12 col-lg-6">
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
        </div>

        <div class="card shadow-sm">

            <div class="card-header bg-light fw-bold text-center">Ajouter une règle NAT / Pare-feu</div>

            <div class="card-body">

                <!-- Création d'une règle de Port-forwarding -->
                <form method="POST">

                    <!-- Champ pour l'appareil -->
                    <div class="mb-3">
                        <label class="form-label">Appareil</label>
                        <input type="text" name="internal_ip" class="form-control" placeholder="192.168.1.10" required>
                    </div>

                    <!-- Champ pour les ports -->
                    <div class="row">

                        <!-- Champ pour le port externe -->
                        <div class="col">
                            <label class="form-label">Port externe</label>
                            <input type="number" name="port_ext" class="form-control" required>
                        </div>

                        <!-- Champ pour le port interne -->
                        <div class="col">
                            <label class="form-label">Port interne</label>
                            <input type="number" name="port_int" class="form-control" required>
                        </div>

                    </div>

                    <!-- Champ pour choisir le protocole (UDP ou TCP) -->
                    <div class="mt-3">
                        <label class="form-label">Protocole</label>
                        <select name="proto" class="form-select">
                            <option value="tcp">TCP</option>
                            <option value="udp">UDP</option>
                        </select>
                    </div>

                    <!-- Bouton pour ajouter la règle -->
                    <button type="submit" name="add_rule" class="btn btn-dark w-100 mt-3">Ajouter la règle</button>

                </form>
            </div>
        </div>

    </div>

    <!-- Si l'utilisateur utilise le mode avancé et qu'il y a au moins une règle personnalisée configurée sur le pare-feu -->
    <?php if($_SESSION['mode'] === 'avance' && !empty($rules)) : ?>
        <div class="card shadow-sm mt-4">

            <div class="card-header bg-light fw-bold text-center">Liste de vos règles personnalisées configurées sur le pare-feu</div>

            <div class="card-body">
                <ul class="list-group">

                    <!-- Parcourt de chaque règle -->
                    <?php foreach ($rules as $rule) :

                        preg_match('/^(\d+).*?(tcp|udp).*?dpt:(\d+).*?to:([0-9\.]+):(\d+)/', $rule, $m);    // Regex pour récupérer les informations qui nous intéresse pour chaque règle

                        // Stockage des variables
                        $rule_num = $m[1];  // Numéro de la règle
                        $proto = $m[2]; // Protocole
                        $port_ext = $m[3];  // Port externe
                        $ip = $m[4];    // Adresse IP
                        $port_int = $m[5];  // Port interne

                    ?>

                        <li class="list-group-item d-flex justify-content-between align-items-center font-monospace">
                            <?= htmlspecialchars("$proto $port_ext → $ip:$port_int") ?>

                            <!-- Formulaire pour supprimer la règle -->
                            <form method="POST" class="ms-2">
                                <input type="hidden" name="rule_num" value="<?= $rule_num ?>">
                                <button type="submit" name="delete_rule" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette règle NAT ?')">Supprimer</button>
                            </form>

                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>
        </div>
    <?php endif; ?>

</main>
