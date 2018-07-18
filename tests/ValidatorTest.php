<?php
namespace Ttskch\ContactForm;

use PHPUnit\Framework\TestCase;
use Ttskch\ContactForm\Session\Errors;
use Ttskch\ContactForm\Session\Submissions;

class ValidatorTest extends TestCase
{
    /**
     * @dataProvider validateProvider
     */
    public function testValidate($keyValues, $requiredKeys, $emailKeys, $expectedMessageKeyValues)
    {
        $submissions = $this->prophesize(Submissions::class);

        // not use mock but actual
        $_SESSION = [];
        $errors = new Errors();

        foreach ($requiredKeys as $key) {
            $submissions->has($key)->willReturn((boolean)$keyValues[$key]);
        }

        foreach ($emailKeys as $key) {
            $submissions->get($key)->willReturn($keyValues[$key]);
        }

        $validator = new Validator($requiredKeys, $emailKeys);
        $validator->validate($submissions->reveal(), $errors);

        if (!count($expectedMessageKeyValues)) {
            $this->assertEquals(0, count($errors->getAll()));
        }

        foreach ($expectedMessageKeyValues as $key => $value) {
            $this->assertEquals($value, $errors->get($key));
        }
    }

    public function validateProvider()
    {
        return [
            [
                ['Name' => 'John', 'Email' => 'john@gmail.com'],
                ['Name', 'Email'],
                ['Email'],
                [],
            ],
            [
                ['Name' => '', 'Email' => 'john'],
                ['Name', 'Email'],
                ['Email'],
                ['Name' => Validator::REQUIRED_MESSAGE, 'Email' => Validator::EMAIL_MESSAGE],
            ],
            [
                ['Name' => 'John', 'Email' => ''],
                ['Name', 'Email'],
                ['Email'],
                ['Email' => Validator::REQUIRED_MESSAGE], // REQUIRED_MESSAGE wins against EMAIL_MESSAGE
            ],
        ];
    }
}
