<?php
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
