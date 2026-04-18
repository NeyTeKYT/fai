<div class="card shadow-sm mt-4">

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