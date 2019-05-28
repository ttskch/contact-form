<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$cf = new \Ttskch\ContactForm\ContactForm();
$cf->rejectAccessWithoutSubmissions('./index.php');

$template = <<<EOT
%s

----------------------------------------------------------------------
Name: %s
----------------------------------------------------------------------
Email: %s
----------------------------------------------------------------------
Gender: %s
----------------------------------------------------------------------
Categories: %s
----------------------------------------------------------------------
Message:
%s
----------------------------------------------------------------------
Main Picture: %s
----------------------------------------------------------------------
Sub Pictures: %s
----------------------------------------------------------------------

%s
EOT;

$configs = require __DIR__ . '/../config.en.php';

foreach ($configs as $config) {
    $body = vsprintf($template, [
        $config['body_head'],
        $cf->present('Name', false),
        $cf->present('Email', false),
        $cf->present('Gender', false),
        $cf->present('Categories', false),
        $cf->present('Message', false),
        $cf->present('Main_Picture.name') ? '(Attached)' : '(None)',
        $cf->present('Sub_Pictures.name.0') ? '(Attached)' : '(None)',
        $config['body_foot'],
    ]);

    $cf->sendEmail(
        @$config['to'] ?: $cf->present('Email', false),
        $config['from'],
        $config['from_name'],
        $config['subject'],
        $body,
        ['Main_Picture', 'Sub_Pictures']
    );
}

$cf->clearSubmissions();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <title>Sample Contact Form</title>
    <style>
        html { font-size: 14px; }
        .form-group > label { font-weight: bold; }
    </style>
</head>
<body class="pt-4">

<div class="container">
    <p class="alert alert-success">Form is successfully submitted!</p>
    <div class="mt-5 text-center">
        <a class="btn btn-link" href="./index.php">Back to top</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
</body>
</html>
