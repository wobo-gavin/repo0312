<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Unfuddle\Command;

/**
 * @group Unfuddle
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ProjectTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Projects\Components\GetComponent
     */
    public function testGetComponents()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.unfuddle');
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Projects\Components\GetComponent(array(
            'projects' => 1
        ));
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'people.get_person'); // We don't care what the response is, just that the request is correct
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertContains('/api/v1/projects/1/components', $command->getRequest()->getUrl());

        // Now get a specific component
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Projects\Components\GetComponent(array(
            'projects' => 1
        ));
        $command->setCompnentId(1);
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'people.get_person'); // We don't care what the response is, just that the request is correct
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertContains('/api/v1/projects/1/components/1', $command->getRequest()->getUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Projects\Severities\GetSeverity
     */
    public function testGetSeverities()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.unfuddle');
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Projects\Severities\GetSeverity(array(
            'projects' => 1
        ));
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'people.get_person'); // We don't care what the response is, just that the request is correct
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertContains('/api/v1/projects/1/severities', $command->getRequest()->getUrl());

        // Now get a specific severity
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Projects\Severities\GetSeverity(array(
            'projects' => 1
        ));
        $command->setSeverityId(1);
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'people.get_person'); // We don't care what the response is, just that the request is correct
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertContains('/api/v1/projects/1/severities/1', $command->getRequest()->getUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Projects\Versions\GetVersion
     */
    public function testGetVersions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.unfuddle');
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Projects\Versions\GetVersion(array(
            'projects' => 1
        ));
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'people.get_person'); // We don't care what the response is, just that the request is correct
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertContains('/api/v1/projects/1/versions', $command->getRequest()->getUrl());

        // Now get a specific severity
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Projects\Versions\GetVersion(array(
            'projects' => 1
        ));
        $command->setVersionId(1);
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'people.get_person'); // We don't care what the response is, just that the request is correct
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertContains('/api/v1/projects/1/versions/1', $command->getRequest()->getUrl());
    }
}