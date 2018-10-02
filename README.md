# contact-form

[![Latest Stable Version](https://poser.pugx.org/ttskch/contact-form/v/stable)](https://packagist.org/packages/ttskch/contact-form)
[![Total Downloads](https://poser.pugx.org/ttskch/contact-form/downloads)](https://packagist.org/packages/ttskch/contact-form)

PHP utility classes to implement general contact form (also with confirmation view). It's maybe useful to build contact form on WordPress or pure PHP site.

## Requirements

* PHP 5.4+
* Configuring `date.timezone` in php.ini

## Supported features

* Csrf protection with session and hidden input tag
* Server side validation for submitted values
* **Short hands to print submitted value itself, validation errors, "selected" option, "checked" option**
* Attaching files and handle them easily in the same session
* Sending email which contains submissions information easily

## Installation

```bash
$ composer require ttskch/contact-form
```

or

```bash
$ git clone git@github.com:ttskch/contact-form.git
$ cd contact-form
$ composer install --no-dev

# If your web site is not composer-friendly, upload whole "contact-form" directory by hand.
```

## Usage

```php
<?php
// index.php

require_once '/path/to/contact-form/vendor/autoload.php';

$cf = new \Ttskch\ContactForm\ContactForm();

// validation targets ("required" and "email" are supported)
$requiredKeys = ['Name', 'Email'];
$emailKeys = ['Email'];

// after posted, validate csrf and submissions and redirect to next page
$cf->validateAndRedirectAfterSelfPosted('./confirm.php', $requiredKeys, $emailKeys);
?>

<!-- post to self -->
<form action="" method="post">
    
    <!-- put hidden input tag for csrf token -->
    <?= $cf->csrfHiddenInput(); ?>

    <!-- text field with submitted value if exists -->
    <input type="text" name="Name" value="<?= $cf->present('Name'); ?>" required autofocus>
    <!-- show error if exists -->
    <p><?= $cf->presentError('Name'); ?></p>
    
    <!-- text field with submitted value if exists -->
    <input type="email" name="Email" value="<?= $cf->present('Email'); ?>" required>
    <!-- show error if exists -->
    <p><?= $cf->presentError('Email'); ?></p>
    
    <!-- selector field with selection submitted or default option -->
    <select class="form-control" name="Gender">
        <option value="Male" <?= $cf->presentSelected('Gender', 'Male', $default = true); ?>>Male</option>
        <option value="Female" <?= $cf->presentSelected('Gender', 'Female'); ?>>Female</option>
        <option value="Other" <?= $cf->presentSelected('Gender', 'Other'); ?>>Other</option>
    </select>

    <button type="submit">Confirm</button>
</form>
```

```php
<?php
// confirm.php

require_once '/path/to/contact-form/vendor/autoload.php';

$cf = new \Ttskch\ContactForm\ContactForm();

// redirect to top page if requested without submissions
$cf->rejectAccessWithoutSubmissions('./index.php');

// after posted, validate csrf and redirect to next page
$cf->validateAndRedirectAfterSelfPosted('./thanks.php');
?>

<!-- post to self -->
<form action="" method="post">

    <!-- put hidden input tag for csrf token -->
    <?= $cf->csrfHiddenInput(); ?>
    
    <!-- show submitted values -->
    <p><?= $cf->present('Name'); ?></p>
    <p><?= $cf->present('Email'); ?></p>
    <p><?= $cf->present('Gender'); ?></p>

    <button type="submit">Send</button>
    
    <!-- can back to index.php and re-edit inputs -->
    <a href="javascript:history.back();">Back</a>

</form>
```

```php
<?php
// thanks.php

require_once '/path/to/contact-form/vendor/autoload.php';

$cf = new \Ttskch\ContactForm\ContactForm();

// redirect to top page if requested without submissions
$cf->rejectAccessWithoutSubmissions('./index.php');

$template = <<<EOT
----------------------------------------------------------------------
Name: %s
----------------------------------------------------------------------
Email: %s
----------------------------------------------------------------------
Gender: %s
----------------------------------------------------------------------
EOT;

$body = vsprintf($template, [
    $cf->present('Name', false),
    $cf->present('Email', false),
    $cf->present('Gender', false),
]);

$cf->sendEmail(
    'you@email.com',  // to
    'from@email.com', // from
    'Your Name',      // from name
    'Got inquiry',    // subject
    $body             // body
);

// clear submissions after sending email
// by this, if users reload thanks.php after sending email they will be redirected to index.php 
$cf->clearSubmissions();
?>

<p>Form is successfully submitted!</p>
```

See [demo](demo) code or run it on your local to learn more :)
