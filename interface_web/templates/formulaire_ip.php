<main>	
	<h1>Formulaire IP</h1>
    <p>Ce formulaire permet de modifier l'adresse IP de la machine.</p>
	<form action="formulaire_ip.php" method="POST">

		<!-- Masque de sous-réseau -->
		<label for="subnet_mask">Masque de sous-réseau:</label><br>
		<section class="octets">

			<?php 
				for($i = 0; $i < 4; $i++) {
					echo "<select name='subnet_mask_octet" . ($i + 1) . "'>";
					foreach($valid_subnet_mask_octet_values as $value) {
						$selected = ($current_subnet_mask_octets[$i] == $value) ? "selected" : "";
						echo "<option value='$value' $selected>$value</option>";
					}
					echo "</select>";
					if($i < 3) echo " . ";	// Ajoute le point entre les octets sauf pour le dernier
				}
			?>

			<br><br>
		</section>
		<br><br>

        <!-- Adresse IP -->
		<label for="ip">Adresse IP :</label><br>

		<!-- 1 input par octet, modifiable ou non en fonction du masque de sous-réseau -->
		<section class="octets">
			<input type="number" id="ip_octet1" name="ip_octet1" min="0" max="255" value="<?php echo explode('.', $current_ip)[0]; ?>" />
			.
			<input type="number" id="ip_octet2" name="ip_octet2" min="0" max="255" value="<?php echo explode('.', $current_ip)[1]; ?>" />
			.
			<input type="number" id="ip_octet3" name="ip_octet3" min="0" max="255" value="<?php echo explode('.', $current_ip)[2]; ?>" />
			.
			<input type="number" id="ip_octet4" name="ip_octet4" min="0" max="255" value="<?php echo explode('.', $current_ip)[3]; ?>" />
			<br><br>
		</section>

		<input type="submit" value="Soumettre">
	</form>
</main>