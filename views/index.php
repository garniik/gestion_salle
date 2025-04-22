<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card bg-dark text-light border-secondary mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="text-warning mb-0">Listes des evenements</h2>
                </div>
                <div class="card-body">
                    <div
                        class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4 mb-5">
                        <?php foreach ($events as $event): ?>
                            <div class="col">
                            <!-- onclick="window.location.href='./index.php?element=pages&action=Card&id=<?= urlencode($event['id']); ?>'" -->
                            <div class="card h-100 bg-dark text-light border-secondary shadow-lg rounded">            
                                <div class="card-body">
                                    <h5 class="card-title"> <?=htmlspecialchars($event['nom']); ?> </h5>
                                    <p class="card-text"> <?=htmlspecialchars($event['jour']); ?> </p>
                                </div>
                            </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

</div>

