<?php
require_once __DIR__ . '/../crest/crest.php';


function getAllDeals($filter = [])
{
    $result = CRest::call('crm.deal.list', [
        'select' => ['*', 'UF_*'],
        'filter' => $filter,
        'order' => ['ID' => 'DESC'],
    ]);
    
    $deals = $result['result'];
    return $deals;
}
