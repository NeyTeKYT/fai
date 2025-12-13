<main class="container my-4">
	<h1 class="mb-4 fw-bold text-dark text-center">Administration de la box <em>Internet</em></h1> <!-- Remplacer "Internet" par le Hostname ? -->

	<!-- Div qui contiendra toutes les "cards" avec les Informations + fonctionnalités simples à implémenter -->
	<div class="row g-4">

		<!-- Configuration du "hostname" de la box Internet -->
		<div class="col-12 col-md-6 col-lg-4">
        	<div class="card shadow-sm h-100">
            	<div class="card-header bg-light text-dark fw-bold">Nom de la box</div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="hostname" class="form-label">Hostname actuel :</label>
                            <input type="text" class="form-control" id="hostname" name="hostname" value="<?php echo $current_hostname; ?>">
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>

		<!-- Informations générales NON MODIFIABLES sur la box Internet -->
		<div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light text-dark fw-bold">Informations générales</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Uptime :</strong> <span><?php echo $uptime; ?></span></p>
                    <p class="mb-2"><strong>Version :</strong> <span><?php echo $os_version; ?></span></p>
                    <p class="mb-2"><strong>Adresse MAC :</strong> <span><?php echo $mac_address; ?></span></p>
                    <p class="mb-0"><strong>Date système :</strong> <span><?php echo $system_date; ?></span></p>
                </div>
            </div>
        </div>

		<!-- Informations sur Apache -->
		<div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light text-dark fw-bold">Serveur Web Apache</div>
                <div class="card-body">
                    <p class="mb-0"><strong>État :</strong> <?php echo $apache_state_span; ?></p>
                </div>
            </div>
        </div>

		<!-- Informations sur DHCP -->
		<!--<article>
			<p id="dhcp-state">Serveur DHCP : <?php echo $dhcp_state_span; ?></p>
			<p id="plage-adresses">Plage d'adresses : </span><?php echo $dhcp_range; ?></span></p>
			<p id="nb-users-connected">Nombre d'utilisateurs connectés : </span><?php echo $dhcp_leases; ?></span></p>
			<p id="users-connected">Utilisateurs connectés : <span><?php echo $dhcp_users; ?></span></p>
			<button>Redémarrer DHCP</button>
		</article>-->

		<!-- Informations sur DNS -->
		<!--<article>
			<p id="dns-sate">Serveur DNS : <?php echo $dns_state_span; ?></p>
			<p id="domain-name">Nom de domain configuré : <span><?php echo $dns_domain; ?></span></p>
			<button>Redémarrer DNS</button>
		</article>-->

		<!-- Mettre à jour la box Internet --> 
		<div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light text-dark fw-bold">Mise à jour</div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <p>Mettre à jour la box Internet vers la dernière version disponible.</p>
                    <button class="btn btn-dark w-100">Lancer la mise à jour</button>
                </div>
            </div>
        </div>

	</div>

</main>