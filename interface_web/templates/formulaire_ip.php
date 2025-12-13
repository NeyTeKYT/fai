<main class="container my-4">
	
	<!-- Titre et rôle de la page -->
	<!-- La version par défaut est la version "débutante" donc j'essaye d'utiliser des termes 
	 simples, différents pour une personne qui ne serait à l'aise avec les termes techniques -->
	<h1 class="mb-4 fw-bold text-dark text-center">Adresse de la box</h1>
    <p class="text-muted mb-4 text-center">Ce formulaire permet de modifier l'adresse de votre box Internet.</p>

	<div class="d-flex justify-content-center">
        <div class="col-12 col-lg-6">

            <div class="card shadow-sm">
                <div class="card-header bg-light text-dark fw-bold text-center">Configuration actuelle</div>
                <div class="card-body">
                    <div class="mb-4">

						<!-- Affichage de l'adresse IP actuelle -->
                        <p class="mb-1"><strong>Adresse actuelle :</strong> <span><?php echo $current_ip; ?></span></p>
						<!-- Affichage du masque de sous-réseau actuel -->
                        <p class="mb-0"><strong>Sous-réseau actuel :</strong> <span><?php echo $current_subnet_mask; ?></span></p>
						
                    </div>

                    <hr>

					<!-- Formulaire de configuration IP -->
                    <form action="formulaire_ip.php" method="POST">

                        <!-- Configuration du masque de sous-réseau -->
                        <div class="mb-3 text-center">
                            <label class="form-label fw-bold">Sous-réseau</label>

                            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-center">
                                <?php 
                                    for($i = 0; $i < 4; $i++) {
                                        echo "<select class='form-select w-auto' name='subnet_mask_octet" . ($i + 1) . "'>";
                                        foreach($valid_subnet_mask_octet_values as $value) {
                                            $selected = ($current_subnet_mask_octets[$i] == $value) ? "selected" : "";
                                            echo "<option value='$value' $selected>$value</option>";
                                        }
                                        echo "</select>";
                                        if($i < 3) echo "<span class='fw-bold'>.</span>";
                                    }
                                ?>
                            </div>
                        </div>

                        <!-- Configuration de l'adresse IP -->
                        <div class="mb-3 text-center">
                            <label class="form-label fw-bold">Adresse</label>

                            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-center">

								<!-- Valeur du premier octet -->
                                <input type="number" class="form-control w-auto" id="ip_octet1" name="ip_octet1" min="0" max="255" 
                                       value="<?php echo explode('.', $current_ip)[0]; ?>">

                                <span class="fw-bold">.</span>

								<!-- Valeur du deuxième octet -->
                                <input type="number" class="form-control w-auto" id="ip_octet2" name="ip_octet2" min="0" max="255" 
                                       value="<?php echo explode('.', $current_ip)[1]; ?>">

                                <span class="fw-bold">.</span>

								<!-- Valeur du troisième octet -->
                                <input type="number" class="form-control w-auto" id="ip_octet3" name="ip_octet3" min="0" max="255" 
                                       value="<?php echo explode('.', $current_ip)[2]; ?>">

                                <span class="fw-bold">.</span>

								<!-- Valeur du quatrième octet -->
                                <input type="number" class="form-control w-auto" id="ip_octet4" name="ip_octet4" min="0" max="255" 
                                       value="<?php echo explode('.', $current_ip)[3]; ?>">
									
                            </div>
                        </div>

						<!-- Bouton pour changer la configuration -->
                        <button type="submit" class="btn btn-dark w-100 mt-3">Soumettre</button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</main>