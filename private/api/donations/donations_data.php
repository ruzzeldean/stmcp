<?php
define('RENDER_HTML', true);
require_once __DIR__ . '/../../includes/helpers.php';

requireLogin();

if (
  !isset($_GET['status']) ||
  !in_array($_GET['status'], ['active', 'closed', 'pending', 'rejected'])
) {
  http_response_code(400);
  echo '<div class="alert alert-danger">Invalid status</div>';
  exit;
}

$status = $_GET['status'];
$db = new Database();

try {
  $perPage = 5;
  $currentPage = max(1, (int)($_GET['page'] ?? 1));
  $offset = ($currentPage - 1) * $perPage;

  $sql = 'SELECT COUNT(*) AS total
        FROM donations AS d
        WHERE d.status = :status';
  $totalRows = $db->fetchOne($sql, ['status' => $status])['total'];

  $totalPages = (int) ceil($totalRows / $perPage);

  if ($currentPage > $totalPages && $totalPages > 0) {
    $currentPage = $totalPages;
    $offset = ($currentPage - 1) * $perPage;
  }

  $sql = 'SELECT
            d.donation_id,
            d.purpose,
            d.start_date,
            d.due_date,
            d.created_by,
            d.status,
            d.approved_by,
            CONCAT(creator.first_name, " ", creator.last_name) AS creator,
            CONCAT(approver.first_name, " ", approver.last_name) AS approver
          FROM donations AS d
          INNER JOIN users AS creator_user
            ON d.created_by = creator_user.user_id
          INNER JOIN people AS creator
            ON creator_user.person_id = creator.person_id
          LEFT JOIN users AS approver_user
            ON d.approved_by = approver_user.user_id
          LEFT JOIN people AS approver
            ON approver_user.person_id = approver.person_id
          WHERE d.status = ?
          ORDER BY d.due_date ASC
          LIMIT ? OFFSET ?';
  $donations = $db->fetchAll($sql, [$status, $perPage, $offset]);

  foreach ($donations as &$donation) {
    $start = new DateTime($donation['start_date']);
    $due = new DateTime($donation['due_date']);

    $donation['start_date'] = $start->format('M j, Y');
    $donation['due_date_formatted'] = $due->format('M j, Y');

    $donation['approver'] = $donation['approver'] ?? '';
  }
  unset($donation);

  ob_start();
?>

  <div class="table-responsive">
    <table class="table table-borderless table-striped table-hover text-nowrap">
      <thead class="bg-tertiary">
        <tr>
          <th>#</th>
          <th>Purpose</th>
          <th>Start Date</th>
          <th>Due Date</th>
          <th>Status</th>
          <?php if ($_SESSION['role_id'] === 2) : ?>
            <th>Created By</th>
            <th>Approved By</th>
          <?php endif; ?>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($donations)) : ?>
          <tr>
            <td class="text-center" colspan="100%">No donations found</td>
          </tr>
        <?php else: ?>
          <?php foreach ($donations as $i => $donation) :
            $badge = match ($donation['status']) {
              'Active' => 'primary',
              'Closed' => 'success',
              'Pending' => 'secondary',
              'Rejected' => 'danger'
            };
          ?>
            <tr>
              <td><?= $offset + $i + 1 ?></td>
              <td><?= e($donation['purpose']) ?></td>
              <td><?= e($donation['start_date']) ?></td>
              <td><?= e($donation['due_date_formatted']) ?></td>
              <td><span class="badge badge-pill bg-<?= $badge ?>"><?= e($donation['status']) ?></span></td>
              <?php if ($_SESSION['role_id'] === 2) : ?>
                <td><?= e($donation['creator']) ?></td>
                <td><?= e($donation['approver'] ?? '') ?></td>
              <?php endif; ?>
              <td>
                <a class="btn btn-primary"
                  href="./donation_details.php?id=<?= e($donation['donation_id']) ?>"
                  title="View">
                  <i class="fa-solid fa-eye"></i>
                </a>
                <?php if ($_SESSION['role_id'] === 2 && $status === 'pending') : ?>
                  <button class="approve-btn btn btn-success" data-donation-id="<?= e($donation['donation_id']) ?>" title="Approve">
                    <i class="fa-solid fa-check"></i>
                  </button>
                  <button class="reject-btn btn btn-danger" data-donation-id="<?= e($donation['donation_id']) ?>" title="Reject">
                    <i class="fa-solid fa-xmark"></i>
                  </button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="row row-gap-5 mt-2">
    <div class="col-lg-6 order-lg-last">
      <?php if ($totalPages > 1): ?>
        <nav aria-label="Donations pagination">
          <ul class="pagination pagination justify-content-center justify-content-lg-end m-0">
            <!-- Previous -->
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
              <a class="page-link ajax-page" href="#" data-page="<?= $currentPage - 1 ?>" aria-label="Previous">
                <span aria-hidden="true">&lsaquo;</span>
              </a>
            </li>

            <?php
            $range = 0;  // Pages to show around current
            $start = max(1, $currentPage - $range);
            $end   = min($totalPages, $currentPage + $range);

            // First page + ellipsis
            if ($start > 1): ?>
              <li class="page-item"><a class="page-link ajax-page" href="#" data-page="1">1</a></li>
              <?php if ($start > 2): ?>
                <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
              <?php endif; ?>
            <?php endif; ?>

            <!-- Page range -->
            <?php for ($i = $start; $i <= $end; $i++): ?>
              <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                <a class="page-link ajax-page" href="#" data-page="<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>

            <!-- Last page + ellipsis -->
            <?php if ($end < $totalPages): ?>
              <?php if ($end < $totalPages - 1): ?>
                <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
              <?php endif; ?>
              <li class="page-item"><a class="page-link ajax-page" href="#" data-page="<?= $totalPages ?>"><?= $totalPages ?></a></li>
            <?php endif; ?>

            <!-- Next -->
            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
              <a class="page-link ajax-page" href="#" data-page="<?= $currentPage + 1 ?>" aria-label="Next">
                <span aria-hidden="true">&rsaquo;</span>
              </a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
    </div>
    <div class="mt-3 mt-lg-0 col-lg-6 order-lg-first d-flex justify-content-center justify-content-lg-start align-items-center">
      <p class="text-muted small my-0">
        Page <strong><?= $currentPage ?></strong> of <strong><?= $totalPages ?></strong>
      </p>
    </div>
  </div>

<?php
  $html = ob_get_clean();
  header('Content-Type: text/html; charset=utf-8');
  echo $html;
  exit;
} catch (Throwable $e) {
  error_log("Error fetching active donations: $e");
  ob_clean();
  echo '<div class="alert alert-warning text-center">Error loading data. Please try again.</div>';
  exit;
}
