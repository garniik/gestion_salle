<div class="container mt-4">
  <!-- Event info -->
  <div class="row">
    <div class="col-12">
      <div class="card bg-dark text-light border-secondary mb-4">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($event['nom']); ?></h5>
          <p class="card-text"><?= htmlspecialchars($event['jour']); ?></p>
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
  <!-- Seats and reservations side by side -->
  <div class="row">
    <!-- Seat grid (left) -->
    <div class="col-md-8">
      <div class="zones-container">
        <?php
        $zones = [
            'Gradins' => ['rows' => ['X', 'W', 'V', 'U', 'T', 'S', 'R', 'Q', 'P', 'O', 'N', 'M', 'L', 'K', 'J', 'I', 'H', 'G', 'F'], 'seats' => 22],
            'Chaises' => ['rows' => ['E5', 'E4', 'E3', 'E2'], 'seats' => 25],
            'Fosse' => ['rows' => ['E1', 'D', 'C', 'B', 'A'], 'seats' => 22],
        ];
        // Liste des places réservées
        $reservedPlaces = array_column($reservations, 'numPlace');
        ?>
        <?php foreach ($zones as $zoneName => $zone): ?>
          <h3 class="mt-4 text-warning"><?= $zoneName ?></h3>
          <div class="zone-container mb-4">
            <?php foreach ($zone['rows'] as $row): ?>
              <div class="d-flex align-items-center mb-2">
                <span class="me-3 fw-bold"><?= $row ?></span>
                <?php for ($seat = 1; $seat <= $zone['seats']; $seat++): ?>
                  <?php
                  $code = "{$row}{$seat}";
                  $missing = ($zoneName === 'Gradins' && (
                      (in_array($row, ['F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O']) && in_array($seat, [3, 20]))
                      || ($row === 'X' && $seat >= 6 && $seat <= 17)
                  ));
                  ?>
                  <?php if ($missing): ?>
                    <button class="btn btn-outline-secondary btn-sm me-1 mb-1 disabled" disabled></button>
                  <?php elseif (in_array($code, $reservedPlaces)): ?>
                    <button class="btn btn-danger btn-sm me-1 mb-1" disabled><?= $seat ?></button>
                  <?php elseif (!isPlaceInRanges($row, $seat, $event['fourchette'])): ?>
                    <button class="btn btn-secondary btn-sm me-1 mb-1 disabled" disabled><?= $seat ?></button>
                  <?php else: ?>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1 mb-1 reserve-seat-btn"
                        data-row="<?= $row ?>" data-seat="<?= $seat ?>"><?= $seat ?></button>
                  <?php endif; ?>
                  <?php if ((($zoneName === 'Gradins' || $zoneName === 'Fosse') && ($seat === 3 || $seat === 19))): ?>
                    <button class="btn btn-outline-secondary btn-sm me-1 mb-1 disabled" disabled></button>
                  <?php endif; ?>
                <?php endfor; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- Reservation list (right) -->
    <div class="col-md-4">
      <div class="card bg-dark text-light border-secondary mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h2 class="text-warning mb-0">Listes des reservations</h2>
          <form class="d-flex" role="search" method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
            <input class="form-control me-2" type="search" name="titre" placeholder="Recherche" aria-label="Search">
            <button class="btn btn-outline-light" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </form>
        </div>
        <div class="card-body">
          <table id="reservationsTable" class="table table-dark table-striped">
            <thead>
              <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Place</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reservations as $res): ?>
                <?php $code = htmlspecialchars($res['numPlace']); ?>
                <tr data-resid="<?= $res['Resid'] ?>">
                  <td><?= htmlspecialchars($res['nom']) ?></td>
                  <td><?= htmlspecialchars($res['prenom']) ?></td>
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
</div>
