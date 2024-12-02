<?php
require_once __DIR__ . '/../crest/crest.php';
require_once __DIR__ . '/../utils/index.php';

include('fetch_deals.php');

$status = isset($_GET['status']) ? $_GET['status'] : null;

$deals = null;

if ($status && $status === 'approved') {
    $deals = getAllDeals(['UF_CRM_1728042953037' => 1295]);
} elseif ($status && $status === 'rejected') {
    $deals = getAllDeals(['UF_CRM_1728042953037' => 1297]);
} else {
    $deals = getAllDeals();
}

$totalAmount = 0;
$totalReferralAmount = 0;

$deals = array_filter($deals, function ($deal) {
    return $deal['UF_CRM_1728042953037'] == 1295 || $deal['UF_CRM_1728042953037'] == 1297;
});

foreach ($deals as &$deal) {
    $deal['OPPORTUNITY'] = formatPrice($deal['OPPORTUNITY']);
    $deal['UF_CRM_1727871887978'] = formatPrice($deal['UF_CRM_1727871887978']);
    $deal['UF_CRM_1727626055823'] = formatPrice($deal['UF_CRM_1727626055823']);

    $deal['ASSIGNED_BY_ID'] = [
        'ID' => $deal['ASSIGNED_BY_ID'],
        'FULL_NAME' => getUserName($deal['ASSIGNED_BY_ID']),
    ];

    $totalAmount += $deal['OPPORTUNITY'];
    $totalReferralAmount += $deal['UF_CRM_1727626055823'];
}

echo json_encode([
    'deals' => $deals,
    'totalDeals' => count($deals),
    'totalAmount' => formatPrice($totalAmount),
    'totalReferralAmount' => formatPrice($totalReferralAmount),
]);
