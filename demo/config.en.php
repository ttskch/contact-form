<?php
return [
    'notification' => [
        'to' => [
            'foo@example.com',
        ],
        'from' => 'noreploy@ttskch.com',
        'from_name' => 'ttskch',
        'subject' => 'Notification of contact',
        'body_head' => "You've got a contact below.",
        'body_foot' => "Regards.",
    ],
    'auto_reply' => [
        'from' => 'noreploy@ttskch.com',
        'from_name' => 'ttskch',
        'subject' => '[auto reply] Thanks for your contact',
        'body_head' => "Thanks for waiting our reply.",
        'body_foot' => "Regards.",
    ],
];
