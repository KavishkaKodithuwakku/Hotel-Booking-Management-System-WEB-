<?php
require_once __DIR__ . '/config.php';
$pageTitle = $pageTitle ?? 'Dashboard';
$pageDescription = $pageDescription ?? 'LuxeStay hotel management admin console.';
$bodyClass = $bodyClass ?? 'admin-body';
$extraCss = $extraCss ?? [];
$isAuthPage = $isAuthPage ?? false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <title><?= htmlspecialchars($pageTitle) ?> | <?= ADMIN_SITE_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="<?= $assetPath ?>/css/admin.css" rel="stylesheet">
    <?php foreach ($extraCss as $css): ?>
    <link href="<?= htmlspecialchars($css) ?>" rel="stylesheet">
    <?php endforeach; ?>
</head>
<body class="<?= htmlspecialchars($bodyClass) ?>">
