<?php
require_once __DIR__ . '/../crest/crest.php';
require_once __DIR__ . '/../utils/index.php';

include('fetch_deals.php');

// $user = getCurrentUser();

$deals = getAllDeals();

foreach ($deals as &$deal) {
    $deal['OPPORTUNITY'] = formatPrice($deal['OPPORTUNITY']);
    $deal['UF_CRM_1727871887978'] = formatPrice($deal['UF_CRM_1727871887978']);
    $deal['UF_CRM_1727626055823'] = formatPrice($deal['UF_CRM_1727626055823']);

    $deal['ASSIGNED_BY_ID'] = [
        'ID' => $deal['ASSIGNED_BY_ID'],
        'FULL_NAME' => getUserName($deal['ASSIGNED_BY_ID']),
    ];
}

echo json_encode([
    'deals' => $deals,
]);
