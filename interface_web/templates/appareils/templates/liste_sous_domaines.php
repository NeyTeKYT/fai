<!-- La card est par défaut cachée au premier chargement -->
<div id="dns-hosts-card" class="d-none">
    <div class="card shadow-sm">

        <div class="card-header bg-light fw-bold text-center">Liste des sous-domaines configurés</div>

        <div class="card-body" style="max-height: 350px; overflow-y: auto;">

            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom d'hôte</th>
                        <th>Adresse IP</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="dns-hosts-table"></tbody>
            </table>

            <p id="no-dns-hosts-msg" class="text-muted text-center d-none">Aucun sous-domaine configuré.</p>

        </div>
    </div>
</div>