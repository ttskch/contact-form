<?php
namespace Ttskch\ContactForm;

use PHPUnit\Framework\TestCase;
use Ttskch\ContactForm\Session\Session;

/**
 * @group Session
 */
class SessionTest extends TestCase
{
    /**
     * @var Session
     */
    private $SUT;

    public function setUp()
    {
        $_SESSION = [
            'base_key' => [
                'key1' => 'value1',
                'key2' => [
                    'key21' => 'value21',
                    'key22' => 'value22',
                ],
                'key3' => [
                    'key31' => ['value311', 'value312'],
                ],
            ],
        ];

        $this->SUT = new Session('base_key');
    }

    /**
     * @dataProvider getProvider
     */
    public function testGet($dotSeparatedKeys, $expectedValue)
    {
        $this->assertEquals($expectedValue, $this->SUT->get($dotSeparatedKeys));
    }

    public function getProvider()
    {
        return [
            ['key1', 'value1'],
            ['key2.key22', 'value22'],
            ['key3.key31', ['value311', 'value312']],
            ['key4', null],
            ['key3.key32', null],
        ];
    }

    /**
     * @dataProvider setProvider
     */
    public function testSet($dotSeparatedKeys, $value, $expectedSessionState)
    {
        $this->SUT->set($dotSeparatedKeys, $value);
        $this->assertEquals($expectedSessionState, $_SESSION['base_key']);
    }

    public function setProvider()
    {
        return [
            ['key1', 'newValue1', [
                'key1' => 'newValue1',
                'key2' => [
                    'key21' => 'value21',
                    'key22' => 'value22',
                ],
                'key3' => [
                    'key31' => ['value311', 'value312'],
                ],
            ]],
            ['key2.key22', 'newValue22', [
                'key1' => 'value1',
                'key2' => [
                    'key21' => 'value21',
                    'key22' => 'newValue22',
                ],
                'key3' => [
                    'key31' => ['value311', 'value312'],
                ],
            ]],
            ['key3.key31.1', 'newValue312', [
                'key1' => 'value1',
                'key2' => [
                    'key21' => 'value21',
                    'key22' => 'value22',
                ],
                'key3' => [
                    'key31' => ['value311', 'newValue312'],
                ],
            ]],
            ['key3.key31.2', 'newValue313', [
                'key1' => 'value1',
                'key2' => [
                    'key21' => 'value21',
                    'key22' => 'value22',
                ],
                'key3' => [
                    'key31' => ['value311', 'value312', 'newValue313'],
                ],
            ]],
            ['key4.key41.0', 'newValue411', [
                'key1' => 'value1',
                'key2' => [
                    'key21' => 'value21',
                    'key22' => 'value22',
                ],
                'key3' => [
                    'key31' => ['value311', 'value312'],
                ],
                'key4' => [
                    'key41' => ['newValue411'],
                ],
            ]],
        ];
    }

    public function testClear()
    {
        $this->SUT->clear();
        $this->assertEquals([], $_SESSION['base_key']);
    }
}
