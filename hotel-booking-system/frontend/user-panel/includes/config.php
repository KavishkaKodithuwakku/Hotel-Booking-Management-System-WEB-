<?php
/**
 * User panel configuration.
 */
define('SITE_NAME', 'LuxeStay');
define('SITE_TAGLINE', 'Luxury Hotel Booking');

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$pagePath = rtrim($scriptDir, '/');
$assetPath = $pagePath . '/assets';
$frontendPath = dirname($pagePath);
$projectPath = dirname($frontendPath);
$apiBaseUrl = $projectPath . '/backend/api';
$adminPath = $frontendPath . '/admin-panel';
