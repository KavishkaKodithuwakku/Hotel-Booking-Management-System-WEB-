<?php
/**
 * Frontend configuration — no database or backend logic.
 */
define('SITE_NAME', 'LuxeStay');
define('SITE_TAGLINE', 'Luxury Hotel Booking');

// Base URL for assets and page links
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$baseUrl = rtrim($scriptDir, '/');
$assetPath = $baseUrl . '/assets';
$pagePath = $baseUrl;
