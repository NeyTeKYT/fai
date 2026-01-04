<main class="container my-4">

	<h1 class="mb-4 fw-bold text-dark text-center">Administration de la box</h1>

	<div class="row g-4">

		<!-- Card pour la configuration du "hostname" de la box Internet -->
		<!--<div class="col-12 col-md-6 col-lg-4">
        	<div class="card shadow-sm h-100">
            	<div class="card-header bg-light text-dark fw-bold text-center">Nom de la box</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="hostname" class="form-label">Nom actuel :</label>
                            <input type="text" class="form-control" id="hostname" name="hostname" value="<?php echo $current_hostname; ?>">
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Modifier le nom de la box</button>
                    </form>
                </div>
            </div>
        </div>-->

		<!-- Card pour les informations générales NON MODIFIABLES sur la box Internet -->
		<div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light text-dark fw-bold text-center">Informations générales</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Lancé depuis :</strong> <span><?php echo $uptime; ?></span></p>
                    <p class="mb-2"><strong>Version de la box :</strong> <span><?php echo $os_version; ?></span></p>
                    <p class="mb-2"><strong>Adresse physique :</strong> <span><?php echo $mac_address; ?></span></p>
                    <p class="mb-0"><strong>Date du système :</strong> <span><?php echo $system_date; ?></span></p>
                </div>
            </div>
        </div>

		<!-- Card pour les informations sur le serveur Apache -->
		<div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light text-dark fw-bold text-center">Serveur Web</div>
                <div class="card-body">
                    <p class="mb-0"><strong>État :</strong> <?php echo $apache_state_span; ?></p>
                </div>
            </div>
        </div>

		<!-- Card pour mettre à jour la box Internet --> 
		<!--<div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light text-dark fw-bold text-center">Mise à jour</div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <p>Mettre à jour la box Internet vers la dernière version disponible.</p>
                    <button class="btn btn-dark w-100">Lancer la mise à jour</button>
                </div>
            </div>
        </div>-->

	</div>

</main>