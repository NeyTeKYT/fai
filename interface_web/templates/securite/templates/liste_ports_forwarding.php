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