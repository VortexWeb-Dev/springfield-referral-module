<?php
require_once __DIR__ . '/../crest/crest.php';


function getAllDeals()
{
    $result = CRest::call('crm.deal.list', [
        'select' => ['*', 'UF_*'],
        'filter' => ['CATEGORY_ID' => 0],
        'order' => ['ID' => 'DESC'],
    ]);
    $deals = $result['result'];
    return $deals;
}
