<!-- Card pour les status des serveurs utiliés pour les différentes fonctionnalités de la box -->
<div class="col-12 col-md-6 col-lg-4">
    <div class="card shadow-sm h-100">
        <div class="card-header bg-light text-dark fw-bold text-center">Status des services</div>
        <div class="card-body">
            <p class="mb-0"><strong>Serveur Apache :</strong> <?php echo $apache_state_span; ?></p>
            <p class="mb-0"><strong>Serveur DHCP :</strong> <?php echo $dhcp_state_span; ?></p>
            <p class="mb-0"><strong>Serveur DNS :</strong> <?php echo $dns_state_span; ?></p>
        </div>
    </div>
</div>