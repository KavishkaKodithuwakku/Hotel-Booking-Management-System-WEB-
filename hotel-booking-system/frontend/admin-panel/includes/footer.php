<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
window.ADMIN_CONFIG = {
    baseUrl: '<?= $pagePath ?>',
    assetPath: '<?= $assetPath ?>',
    apiBaseUrl: '<?= $apiBaseUrl ?>',
    userPanelUrl: '<?= $userPanelPath ?>'
};
window.LUXE_CONFIG = { apiBaseUrl: '<?= $apiBaseUrl ?>' };
</script>
<script src="<?= $userPanelPath ?>/assets/js/api-client.js"></script>
<script src="<?= $assetPath ?>/js/api-admin.js"></script>
<script src="<?= $assetPath ?>/js/admin.js"></script>
<?php if (!empty($extraJs)): foreach ($extraJs as $js): ?>
<script src="<?= htmlspecialchars($js) ?>"></script>
<?php endforeach; endif; ?>
</body>
</html>
