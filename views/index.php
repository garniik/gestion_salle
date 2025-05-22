<div class="card bg-dark text-light border-secondary mb-4 w-100">
    <div class="row">
        <div class="col-12">
            <div class="card-header d-flex justify-content-between align-items-center">
                
                <h2 class="mb-0">Listes des evenements</h2>
                <div class="d-flex">
                    <input id="event-search" class="form-control me-2" type="search" placeholder="Rechercher un evenement" aria-label="Search">
                </div>
            </div>
            <div class="card-body">
                <div
                    class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4 mb-5">
                    <?php foreach ($events as $event):?>
                        <div class="col-auto">
                            <a href="index.php?element=pages&action=reservation&id=<?= urlencode($event['eventid']);  ?>" class="text-decoration-none">
                                <div class="card event-card h-100 bg-dark text-light border-secondary shadow-lg rounded">            
                                    <div class="card-body">
                                        <h5 class="card-title"> <?= $event['nom']; ?> </h5>
                                        <p class="card-text"> <?= $event['jour']; ?> </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
