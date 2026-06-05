<?php
/**
 * One-time installer — creates DB, imports schema, seeds data, sets passwords.
 * Visit: /hotel-booking-system/backend/install.php
 * DELETE this file after installation in production.
 */
header('Content-Type: text/html; charset=utf-8');

$dbConfig = require __DIR__ . '/config/database.php';
$schema = file_get_contents(__DIR__ . '/database/schema.sql');
$seed = file_get_contents(__DIR__ . '/database/seed.sql');

$messages = [];
$ok = true;

try {
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $dbConfig['host'], $dbConfig['port']),
        $dbConfig['username'],
        $dbConfig['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $pdo->exec($schema);
    $messages[] = 'Schema created successfully.';

    $pdo->exec($seed);
    $messages[] = 'Seed data imported.';

    // Sync migrations — run each statement separately so duplicate columns are ignored
    $pdo->exec('USE ' . $dbConfig['dbname']);
    $migrateSQL = file_get_contents(__DIR__ . '/database/migrate_sync.sql');
    // Strip comments and split on semicolons
    $migrateSQL = preg_replace('/--[^\n]*\n/', "\n", $migrateSQL);
    $statements = array_filter(array_map('trim', explode(';', $migrateSQL)));
    $migErrors = 0;
    foreach ($statements as $stmt) {
        if (!$stmt) continue;
        try { $pdo->exec($stmt); } catch (Throwable $me) { $migErrors++; }
    }
    $messages[] = 'Sync migrations applied' . ($migErrors ? " ($migErrors already-existed statements skipped)" : '') . '.';


    $pdo->exec('USE ' . $dbConfig['dbname']);
    $hashAdmin = password_hash('admin123', PASSWORD_DEFAULT);
    $hashUser = password_hash('user123', PASSWORD_DEFAULT);
    $pdo->prepare('UPDATE users SET password = ? WHERE email = ?')->execute([$hashAdmin, 'admin@luxestay.com']);
    $pdo->prepare('UPDATE users SET password = ? WHERE email = ?')->execute([$hashUser, 'user@luxestay.com']);
    $pdo->prepare('UPDATE users SET password = ? WHERE role = ? AND email != ?')->execute([$hashUser, 'customer', 'admin@luxestay.com']);
    $messages[] = 'Passwords set: admin@luxestay.com / admin123, user@luxestay.com / user123';

    $messages[] = 'API ready at: /hotel-booking-system/backend/api/';
} catch (Throwable $e) {
    $ok = false;
    $messages[] = 'Error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html><head><title>LuxeStay Install</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-5"><div class="container col-md-8">
<h1>LuxeStay Backend Installer</h1>
<div class="alert alert-<?= $ok ? 'success' : 'danger' ?>">
<?php foreach ($messages as $m): ?><p class="mb-1"><?= htmlspecialchars($m) ?></p><?php endforeach; ?>
</div>
<?php if ($ok): ?>
<p><strong>Next:</strong> Open the <a href="../frontend/index.php">frontend</a> and test login.</p>
<p class="text-danger small">Delete install.php after setup for security.</p>
<?php endif; ?>
</div></body></html>
