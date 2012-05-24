<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Validation;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\AnyMatch;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\AnyMatch
 */
class AnyMatchTest extends Validation
{
    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testRequiresInspector()
    {
        $c = new AnyMatch();
        $c->validate('foo', array(
            'constraints' => 'string'
        ));
    }

    public function provider()
    {
        $c = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation\AnyMatch';

        if (!class_exists('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector')) {
            $this->markTestSkipped('Inspector not present');
        }

        $i = Inspector::getInstance();

        return array(
            array($c, 'a', array('constraints' => 'type:string', 'inspector' => $i), true, null),
            array($c, 'a', array('type:string', 'inspector' => $i), true, null),
            array($c, 'foo', array('constraints' => 'type:string;type:numeric', 'inspector' => $i), true, null),
            array($c, new \stdClass(), array('constraints' => 'type:string;type:numeric', 'inspector' => $i), 'Value type must match one of type:string OR type:numeric', null),
            array($c, 'foo', array('constraints' => 'type:numeric;type:boolean;ip;email', 'inspector' => $i), 'Value type must match one of type:numeric OR type:boolean OR ip OR email', null),
            array($c, 'http://www.example.com', array('constraints' => 'ip;url', 'inspector' => $i), true, null),
            array($c, '192.168.16.148', array('constraints' => 'ip;url', 'inspector' => $i), true, null),
            array($c, 'foo', array('constraints' => 'email;choice:foo,bar;ip;array', 'inspector' => $i), true, null),
            array($c, 'bar', array('constraints' => 'email;choice:foo,bar;ip;array', 'inspector' => $i), true, null),
            array($c, '192.168.16.48', array('constraints' => 'email;choice:foo,bar;ip;array', 'inspector' => $i), true, null),
            array($c, array(), array('constraints' => 'email;choice:foo,bar;ip;array', 'inspector' => $i), true, null),
            array($c, 'michael@awesome.com', array('constraints' => 'email;choice:foo,bar;ip;array', 'inspector' => $i), true, null),
            array($c, new \stdClass(), array('constraints' => 'type:object', 'inspector' => $i), true, null)
        );
    }
}
