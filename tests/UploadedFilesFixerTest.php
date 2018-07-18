<?php
namespace Ttskch\ContactForm;

use PHPUnit\Framework\TestCase;

class UploadedFilesFixerTest extends TestCase
{
    /**
     * @var UploadedFilesFixer
     */
    protected $fixer;

    public function setUp()
    {
        $this->fixer = new UploadedFilesFixer();
    }

    public function testFix()
    {
        $_FILES = [
            '項目1' => [
                'name' => 'ファイル名1',
                'type' => 'type1',
                'tmp_name' => '/tmp/name/1',
                'error' => 1,
                'size' => 1,
            ],
        ];

        $this->fixer->fix();

        $this->assertContains(UploadedFilesFixer::TEMPNAM_PREFIX, $_FILES['項目1']['tmp_name']);
    }
}
