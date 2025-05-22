<div class="container-fluid mt-4">
  <!-- Information evenements -->
  <div class="row">
    <div class="col-12">
      <div class="card bg-dark text-light border-secondary mb-4">
        <div class="card-body   ">
          <h5 class="card-title"><?= $event['nom']; ?></h5>
          <p class="card-text"><?= $event['jour']; ?></p>
          <p class="card-text">Prix place : <?= htmlspecialchars($event['prix'] ?? 0) ?> €</p>
          <p class="card-text">Total collecté : <?= htmlspecialchars(number_format((count($reservations) * ($event['prix'] ?? 0)), 2, ',', ' ')) ?> €</p>
          <td>
            <form method="POST"  onsubmit="return confirm('Voulez-vous vraiment supprimer cette evenement ?');">
              <input type="hidden" name="delete_id" value="<?= $event['eventid'] ?>">
              <button type="submit" name="deleteEvent" class="btn btn-danger">Supprimer</button>
            </form>
          </td>
        </div>
      </div>
    </div>
  </div>

    <!-- Grilles des sièges-->
    <div class="col-12">
      <div class="zones-container">
        <?php
        $zones = [
            'Gradins' => ['rows' => ['X', 'W', 'V', 'U', 'T', 'S', 'R', 'Q', 'P', 'O', 'N', 'M', 'L', 'K', 'J', 'I', 'H', 'G', 'F'], 'place' => 22],
            'Chaises' => ['rows' => ['E5', 'E4', 'E3', 'E2'], 'place' => 25],
            'Fosse' => ['rows' => ['E1', 'D ', 'C', 'B', 'A'], 'place' => 22],
        ];
        $reservedPlaces = array_column($evenement->getReservation($eventid), 'numPlace');
        foreach ($zones as $zoneName => $zone): ?>
          <h3 class="mt-4 text-center"><?= $zoneName ?></h3>
          <div class="zone-container mb-4 d-flex justify-content-center">
            <table class="table-sm">
              <tbody>
              <?php foreach ($zone['rows'] as $row): ?>
                <tr>
                  <th class="row-label"><?= $row ?></th>
                  <?php for ($place = 1; $place <= $zone['place']; $place++):
                    $code = "{$row}{$place}";
                    $missing = ($zoneName === 'Gradins' && (
                        (in_array($row, ['F','G','H','I','J','K','L','M','N','O']) && in_array($place, [3,20]))
                        || ($row === 'X' && $place >= 6 && $place <= 17)
                    ));
                    ?>
                    <td>
                      <?php if ($missing): ?>
                        <button class="btn btn-outline-secondary btn-sm disabled" disabled></button>
                      <?php elseif (in_array($code, $reservedPlaces)): ?>
                        <button class="btn btn-danger btn-sm" disabled><?= $place ?></button>
                      <?php elseif (!isPlaceInRanges($row, $place, $event['fourchette'])): ?>
                        <button class="btn btn-secondary btn-sm disabled" disabled><?= $place ?></button>
                      <?php else: ?>
                        <button type="button" class="btn btn-outline-secondary btn-sm reserve-place-btn" data-row="<?= $row ?>" data-place="<?= $place ?>"><?= $place ?></button>
                      <?php endif; ?>
                    </td>
                    <?php if (($zoneName === 'Gradins' || $zoneName === 'Fosse') && ($place === 3 || $place === 19)): ?>
                      <td>
                        <button class="btn btn-outline-secondary btn-sm disabled" disabled></button>
                      </td>
                    <?php endif;
                      endfor; ?>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endforeach; ?>
      </div>

  </div>
    <!-- Liste des reservations -->
  <div class="col-12">
      <div class="card bg-dark text-light border-secondary mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h2 class="mb-0">Listes des reservations</h2>
          <div class="d-flex">
            <input id="reservation-search" class="form-control me-2" type="search" placeholder="Recherche" aria-label="Search">
          </div>
        </div>
        <div class="card-body">
          <table id="reservationsTable" class="table table-dark table-striped text-center">
            <thead>
              <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Adresse</th>
                <th>Place</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reservations as $res):
                $code = $res['numPlace']; ?>
                <tr data-resid="<?= $res['Resid'] ?>">
                  <td><?= htmlspecialchars($res['nom'] ?? '') ?></td>
                  <td><?= htmlspecialchars($res['prenom'] ?? '') ?></td>
                  <td><?= htmlspecialchars($res['telephone'] ?? '') ?></td>
                  <td><?= htmlspecialchars($res['email'] ?? '') ?></td>
                  <td><?= htmlspecialchars($res['adresse'] ?? '') ?></td>
                  <td><?= $code ?></td>
                  <td>
                    <form method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette réservation ?');">
                      <input type="hidden" name="delete_id" value="<?= $res['Resid'] ?>">
                      <button type="submit" name="deleteRes" class="btn btn-danger">Supprimer</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
</div>

<!-- Réservation-->
<div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header">
        <h5 class="modal-title" id="reserveModalLabel">Réservation Place <span id="modalplaceCode"></span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="reserveForm" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
        <div class="modal-body">
          <input type="hidden" name="numPlace" id="reserveNumPlace" value="">
          <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" name="nom" id="nom" required>
          </div>
          <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" name="prenom" id="prenom" required>
          </div>
          <div class="mb-3">
            <label for="numTel" class="form-label">Numéro de téléphone</label>
            <input type="text" class="form-control" name="numTel" id="numTel" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" required>
          </div>
          <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text" class="form-control" name="adresse" id="adresse" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" name="reserve" class="btn btn-primary">Réserver</button>
        </div>
      </form>
    </div>
  </div>
</div>
