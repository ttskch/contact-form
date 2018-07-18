<?php
return [
    'notification' => [
        'to' => [
            'ttskch@gmail.com',
        ],
        'from' => 'noreploy@ttskch.com',
        'from_name' => '株式会社たつきち',
        'subject' => 'お問い合わせがありました',
        'body_head' => "以下のお客様からお問い合わせがありました。",
        'body_foot' => "-- \n株式会社たつきち",
    ],
    'auto_reply' => [
        'from' => 'noreploy@ttskch.com',
        'from_name' => '株式会社たつきち',
        'subject' => '【自動返信メール】お問い合わせありがとうございました',
        'body_head' => "この度は、株式会社たつきちへお問い合わせをいただき誠にありがとうございました。\n内容を確認次第、担当よりご連絡を差し上げますので、今しばらくお待ちください。",
        'body_foot' => "-- \n株式会社たつきち",
    ],
];
