<?php
require_once __DIR__ . '/../vendor/autoload.php';

$cf = new \Ttskch\ContactForm\ContactForm();
$cf->validateAndRedirectAfterSelfPosted('./confirm.php', ['Name', 'Email', 'Main_Picture', 'Sub_Pictures'], ['Email']);
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
    <form action="" method="post" enctype="multipart/form-data" novalidate>
        <?= $cf->csrfHiddenInput(); ?>

        <div class="form-group">
            <label>Name <span style="color:#d00">*</span></label>
            <input class="form-control <?= $cf->hasError('Name') ? 'is-invalid' : ''; ?>" type="text" name="Name" value="<?= $cf->present('Name'); ?>" required autofocus>
            <span class="invalid-feedback"><?= $cf->presentError('Name'); ?></span>
        </div>

        <div class="form-group">
            <label>Email <span style="color:#d00">*</span></label>
            <input class="form-control <?= $cf->hasError('Email') ? 'is-invalid' : '' ?>" type="email" name="Email" value="<?= $cf->present('Email'); ?>" required>
            <span class="invalid-feedback"><?= $cf->presentError('Email'); ?></span>
        </div>

        <div class="form-group">
            <label>Gender</label>
            <select class="form-control" name="Gender">
                <option value="Male" <?= $cf->presentSelected('Gender', 'Male', true); ?>>Male</option>
                <option value="Female" <?= $cf->presentSelected('Gender', 'Female'); ?>>Female</option>
                <option value="Other" <?= $cf->presentSelected('Gender', 'Other'); ?>>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Category</label>
            <div class="form-check form-check-inline d-block">
                <label class="form-check-label mr-2">
                    <input class="form-check-input" type="radio" name="Category" value="Cat-A" <?= $cf->presentChecked('Category', 'Cat-A', true); ?>>Cat-A
                </label>
                <label class="form-check-label mr-2">
                    <input class="form-check-input" type="radio" name="Category" value="Cat-B" <?= $cf->presentChecked('Category', 'Cat-B'); ?>>Cat-B
                </label>
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="Category" value="Cat-C" <?= $cf->presentChecked('Category', 'Cat-C'); ?>>Cat-C
                </label>
            </div>
        </div>

        <div class="form-group">
            <label>Tags</label>
            <div class="form-check form-check-inline d-block">
                <label class="form-check-label mr-2">
                    <input class="form-check-input" type="checkbox" name="Tags[]" value="Tag-A" <?= $cf->presentChecked('Tags', 'Tag-A'); ?>>Tag-A
                </label>
                <label class="form-check-label mr-2">
                    <input class="form-check-input" type="checkbox" name="Tags[]" value="Tag-B" <?= $cf->presentChecked('Tags', 'Tag-B'); ?>>Tag-B
                </label>
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="Tags[]" value="Tag-C" <?= $cf->presentChecked('Tags', 'Tag-C'); ?>>Tag-C
                </label>
            </div>
        </div>

        <div class="form-group">
            <label>Message</label>
            <textarea class="form-control" name="Message" rows="4"><?= $cf->present('Message'); ?></textarea>
        </div>

        <div class="form-group">
            <label>Main Picture <span style="color:#d00">*</span></label>
            <input class="form-control <?= $cf->hasError('Main_Picture') ? 'is-invalid' : ''; ?>" type="file" name="Main_Picture" accept="image/*" required>
            <span class="invalid-feedback"><?= $cf->presentError('Main_Picture'); ?></span>
        </div>

        <div class="form-group">
            <label>Sub Pictures <span style="color:#d00">*</span></label>
            <input class="form-control <?= $cf->hasError('Sub_Pictures') ? 'is-invalid' : ''; ?>" type="file" name="Sub_Pictures[]" accept="image/*" multiple required>
            <span class="invalid-feedback"><?= $cf->presentError('Sub_Pictures'); ?></span>
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Confirm</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
</body>
</html>
