<?php
require_once __DIR__ . '/../crest/crest.php';

function getUser($user_id)
{
    $result = CRest::call('user.get', ['ID' => $user_id]);
    $user = $result['result'][0];
    return $user;
}

function getCurrentUser()
{
    $result = CRest::call('user.current', []);
    $user = $result['result'];
    return $user;
}

function isAdmin($user_id)
{
    $admins = [
        263, // Madushika Kudagodage
        273, // Farooq Syed
        279, // Anne Lacad,
        8 // VortexWeb
    ];

    return in_array($user_id, $admins);
}
