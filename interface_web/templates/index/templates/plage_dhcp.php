<div class="col-12 col-md-6 col-lg-4">
    <div class="card shadow-sm h-100">

        <!-- Titre et description en fonction du mode de configuration de l'utilisateur -->
        <?php if($_SESSION['mode'] === 'debutant') : ?>
            <div class="card-header bg-light text-dark fw-bold text-center">Nombre de machines pouvant être au maximum connectées à la box</div>
        <?php else : ?>
            <div class="card-header bg-light text-dark fw-bold text-center">Plage d'adresses IP DHCP</div>
        <?php endif; ?>

        <div class="card-body">

            <?php if($_SESSION['mode'] === 'debutant') : ?>
                <p class="mb-0"><strong>Nombre de machines configurées :</strong> <?php echo $current_configured_devices_number; ?></p>
            <?php else : ?>
                <p class="mb-0"><strong>Plage :</strong> <?php echo $dhcp_range; ?></p>
            <?php endif; ?>

        </div>
    </div>
</div>