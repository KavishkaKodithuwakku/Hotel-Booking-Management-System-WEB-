<?php
/**
 * Admin panel configuration.
 */
define('SITE_NAME', 'LuxeStay');
define('ADMIN_SITE_NAME', 'LuxeStay Admin');
define('SITE_TAGLINE', 'Hotel Management Console');

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$pagePath = rtrim($scriptDir, '/');
$assetPath = $pagePath . '/assets';
$frontendPath = dirname($pagePath);
$projectPath = dirname($frontendPath);
$apiBaseUrl = $projectPath . '/backend/api';
$userPanelPath = $frontendPath . '/user-panel';
