<?php
require_once __DIR__ . '/../crest/crest.php';

$dealId = $_GET['dealId'] ?? null;
$newStatus = $_GET['newStatus'] ?? null;

$status = $_GET['status'] ?? null;

$redirect_url = '../index.php' . (isset($status) ? '?status=' . urlencode($status) : 'all');

if ($dealId && $status) {
    $result = CRest::call('crm.deal.update', [
        'id' => $dealId,
        'fields' => [
            'UF_CRM_1728042953037' => $newStatus,
        ],
    ]);

    if (isset($result['result']) && $result['result']) {
        header("Location: $redirect_url&message=Deal updated successfully.");
        exit;
    } else {
        header("Location: $redirect_url&message=Failed to update deal.");
        exit;
    }
} else {
    header("Location: $redirect_url&message=Invalid deal ID.");
    exit;
}
