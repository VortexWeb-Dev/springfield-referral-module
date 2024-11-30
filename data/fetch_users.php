<?php
require_once __DIR__ . '/../crest/crest.php';

function getUser($user_id)
{
    $result = CRest::call('user.get', ['ID' => $user_id]);
    $user = $result['result'][0];
    return $user;
}