<main>
	<h1>Bienvenue sur l'interface web de votre box <em>Internet</em> !</h1> <!-- Remplacer "Internet" par le Hostname ? -->

	<!-- Div qui contiendra toutes les "cards" avec les inarticleations + fonctionnalités simples à implémenter -->
	<div>

		<!-- Configuration du "hostname" de la box Internet -->
		<form>
			<label for="hostname">Nom de la box :</label><br>
			<input type="text" id="hostname" name="hostname" value="<?php echo $current_hostname; ?>">
			<input type="submit" value="Soumettre">
		</form>

		<!-- Inarticleations générales NON MODIFIABLES sur la box Internet -->
		<article>
			<p id="uptime">Fonctionne depuis : <span><?php echo $uptime; ?></span></p>
			<p id="os-version">Version : <span><?php echo $os_version; ?></span></p>
			<p id="mac-address">Adresse MAC : <span><?php echo $mac_address; ?></span></p>
			<p id="date">Date du système : <span><?php echo $system_date; ?></span></p>
		</article>

		<!-- Inarticleations sur le réseau -->
		<article>
			<p id="ip-address">Adresse IP : <span><?php echo $current_ip; ?></span></p>
			<p id="netmask">Masque de sous-réseau : <span><?php echo $current_subnet_mask; ?></span></p>
		</article>

		<!-- Inarticleations sur Apache -->
		<article>
			<p id="apache-state">Serveur Web : <?php echo $apache_state_span; ?></p>
		</article>

		<!-- Inarticleations sur DHCP -->
		<article>
			<p id="dhcp-state">Serveur DHCP : <?php echo $dhcp_state_span; ?></p>
			<p id="plage-adresses">Plage d'adresses : </span><?php echo $dhcp_range; ?></span></p>
			<p id="nb-users-connected">Nombre d'utilisateurs connectés : </span><?php echo $dhcp_leases; ?></span></p>
			<p id="users-connected">Utilisateurs connectés : <span><?php echo $dhcp_users; ?></span></p>
			<button>Redémarrer DHCP</button>
		</article>

		<!-- Inarticleations sur DNS -->
		<article>
			<p id="dns-sate">Serveur DNS : <?php echo $dns_state_span; ?></p>
			<p id="domain-name">Nom de domain configuré : <span><?php echo $dns_domain; ?></span></p>
			<button>Redémarrer DNS</button>
		</article>

		<!-- Mettre à jour la box Internet --> 
		<article>
			<button>Mettre à jour la box Internet</button>
		</article>

	</div>

</main>