<?php require ROOT_PATH . '/app/views/layouts/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0 fw-bold">Log Aktivitas</h3>
        <small class="text-muted">Jejak audit aksi penting di dalam sistem</small>
    </div>
    <form action="<?= BASEURL ?>/logs" method="get" class="d-flex gap-2">
        <input type="text" name="action" class="form-control form-control-sm"
               placeholder="Filter aksi, mis. item." value="<?= e($action) ?>">
        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i></button>
        <?php if ($action !== ''): ?>
            <a href="<?= BASEURL ?>/logs" class="btn btn-sm btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Aksi</th>
                        <th>Entitas</th>
                        <th>Keterangan</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs['data'])): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada aktivitas tercatat.</td>
                        </tr>
                    <?php else: ?>
                        <?php
                        $no = ($logs['current_page'] - 1) * 30 + 1;
                        foreach ($logs['data'] as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="text-nowrap"><?= tanggal($row['created_at'], true) ?></td>
                                <td>
                                    <?php if ($row['username']): ?>
                                        <?= e($row['full_name'] ?: $row['username']) ?>
                                        <small class="text-muted">(<?= e($row['username']) ?>)</small>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><code><?= e($row['action']) ?></code></td>
                                <td>
                                    <?= e($row['entity'] ?: '-') ?>
                                    <?php if ($row['entity_id']): ?>
                                        <span class="text-muted">#<?= e($row['entity_id']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted"><?= e($row['description'] ?: '-') ?></td>
                                <td><small class="text-muted"><?= e($row['ip_address'] ?: '-') ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

         <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">Total <?= e($logs['total_data']) ?> aktivitas</small>
            <?php if ($logs['total_pages'] > 1):
                $q = ['action' => $action];
            ?>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?= $logs['current_page'] <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query($q + ['page' => $logs['current_page'] - 1]) ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $logs['total_pages']; $i++): ?>
                            <li class="page-item <?= $i === $logs['current_page'] ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query($q + ['page' => $i]) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $logs['current_page'] >= $logs['total_pages'] ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query($q + ['page' => $logs['current_page'] + 1]) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>