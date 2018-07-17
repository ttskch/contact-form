<?php
/**
 * This file is part of the Ttskch.ContactForm
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ttskch\ContactForm;

use PHPUnit\Framework\TestCase;

class ContactFormTest extends TestCase
{
    /**
     * @var ContactForm
     */
    protected $contactForm;

    protected function setUp()
    {
        $this->contactForm = new ContactForm;
    }

    public function testIsInstanceOfContactForm()
    {
        $actual = $this->contactForm;
        $this->assertInstanceOf(ContactForm::class, $actual);
    }
}
