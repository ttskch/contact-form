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
    public function testValidate($submittedData, $requiredKeys, $emailKeys, $expectedMessageKeyValues)
    {
        // not use mock but actual
        $_SESSION = [];
        $_POST = @$submittedData['$_POST'] ?: [];
        $_FILES = @$submittedData['$_FILES'] ?: [];
        $submissions = new Submissions(new UploadedFilesFixer());
        $submissions->initialize();
        $errors = new Errors();

        $validator = new Validator($requiredKeys, $emailKeys);
        $validator->validate($submissions, $errors);

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
                [
                    '$_POST' => ['Name' => 'john', 'Email' => 'john@gmail.com'],
                ],
                ['Name', 'Email'],
                ['Email'],
                [],
            ],
            [
                [
                    '$_POST' => ['Name' => '', 'Email' => 'john@gmail'],
                ],

                ['Name', 'Email'],
                ['Email'],
                ['Name' => Validator::DEFAULT_REQUIRED_MESSAGE, 'Email' => Validator::DEFAULT_EMAIL_MESSAGE],
            ],
            [
                [
                    '$_POST' => ['Name' => 'john', 'Email' => ''],
                ],
                ['Name', 'Email'],
                ['Email'],
                ['Email' => Validator::DEFAULT_REQUIRED_MESSAGE], // "required message" wins against "email message"
            ],
            [
                [
                    '$_FILES' => [
                        'File' => [
                            'name' => '',
                            'tmp_name' => '',
                        ],
                        'Files' => [
                            'name' => ['', ''],
                            'tmp_name' => ['', ''],
                        ],
                    ],
                ],
                ['File', 'Files'],
                [],
                ['File' => Validator::DEFAULT_REQUIRED_MESSAGE, 'Files' => Validator::DEFAULT_REQUIRED_MESSAGE]
            ],
        ];
    }
}
