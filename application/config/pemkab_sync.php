<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Credentials stay on the server; never included in API payloads, UI, or logs.
$config['pemkab_sync'] = [
    'base_url' => getenv('PRP2_SUMUT_API_URL') ?: '',
    'token' => getenv('PRP2_SUMUT_API_TOKEN') ?: '',
    'enabled' => getenv('PRP2_SYNC_ENABLED') === '1',
    'storage_path' => getenv('PRP2_STORAGE_PATH') ?: dirname(rtrim(FCPATH, '/\\'), 2).'/prp2pemkab-data/'.substr(hash('sha256', realpath(FCPATH)), 0, 16),
    'max_photo_bytes' => 5 * 1024 * 1024,
    'timeout' => 30
];
