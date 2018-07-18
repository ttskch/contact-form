<?php
require_once __DIR__ . '/../vendor/autoload.php';

$cf = new \Ttskch\ContactForm\ContactForm();
$cf->rejectAccessWithoutSubmissions('./index.php');
$cf->validateAndRedirectAfterSelfPosted('./thanks.php');
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
    <form action="" method="post">
        <?= $cf->csrfHiddenInput(); ?>

        <div class="form-group">
            <label>Name <span style="color:#d00">*</span></label>
            <p><?= $cf->present('Name'); ?></p>
        </div>

        <div class="form-group">
            <label>Email <span style="color:#d00">*</span></label>
            <p><?= $cf->present('Email'); ?></p>
        </div>

        <div class="form-group">
            <label>Gender</label>
            <p><?= $cf->present('Gender'); ?></p>
        </div>

        <div class="form-group">
            <label>Category</label>
            <p><?= $cf->present('Category'); ?></p>
        </div>

        <div class="form-group">
            <label>Tags</label>
            <p><?= $cf->present('Tags'); ?></p>
        </div>

        <div class="form-group">
            <label>Message</label>
            <p><?= $cf->present('Message', true, true); ?></p>
        </div>

        <div class="form-group">
            <label>Main Picture</label>
            <p><?= $cf->present('Main_Picture.name', true, false, '<code>%s</code>'); ?></p>
        </div>

        <div class="form-group">
            <label>Sub Pictures</label>
            <p><?= $cf->present('Sub_Pictures.name', true, false, '<code>%s</code>'); ?></p>
        </div>

        <div class="form-group">
            <button class="btn btn-primary mr-2" type="submit">Submit</button>
            <a class="btn btn-outline-secondary" href="javascript:history.back();">Back</a>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
</body>
</html>
