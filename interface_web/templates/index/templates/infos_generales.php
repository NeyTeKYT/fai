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