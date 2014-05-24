<?php
/**
 * Projectshift
 *
 * LICENSE
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file license/projectshift.mit.txt
 * It is also available through the world-wide-web at this URL:
 * http://projectshift.eu/license/mit
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@projectshift.eu so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2010 Webcomplex LLC (http://www.projectshift.eu)
 * @license    http://projectshift.eu/license/mit     MIT License
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 */

/**
 * @namespace
 */
namespace ShiftTest\Integration\ShiftContentNew\Doctrine;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Doctrine\DiscriminatorSubscriber;
use Doctrine\ORM\Events;

/**
 * This holds integration tests for field settings discriminator subscriber.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Test
 *
 * @group       integration
 */
class DiscriminatorSubscriberTest extends TestCase
{

    /**
     * Doctrine entity manager
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Set up environment to run our tests
     */
    public function setUp()
    {
        parent::setUp();

        //set entity manager
        $doctrine = $this->sm()->get('ShiftDoctrine\Container');
        $this->em = $doctrine->getEntityManager();
    }

    // ------------------------------------------------------------------------


    /**
     * Test that we can instantiate subscriber
     * @test
     */
    public function canInstantiateSubscriber()
    {
        $subscriber = new DiscriminatorSubscriber;
        $this->assertInstanceOf(
            'ShiftContentNew\Doctrine\DiscriminatorSubscriber',
            $subscriber
        );
    }


    /**
     * Test that we are able to inject arbitrary config into listener.
     * @test
     */
    public function canInjectConfig()
    {
        $config = array('me is config');
        $subscriber = new DiscriminatorSubscriber;
        $subscriber->setConfig($config);
        $this->assertEquals($config, $subscriber->getConfig());
    }


    /**
     * Test that we do obtain config from bootstrap if none injected.
     * @test
     */
    public function canGetConfigFromBootstrapIfNoneInjected()
    {
        $subscriber = new DiscriminatorSubscriber;
        $config = $subscriber->getConfig();
        $this->assertTrue(is_array($config));
        $this->assertFalse(empty($config));
    }


    /**
     * Test that we are able to build map from configured fields.
     * @test
     */
    public function canBuildMap()
    {
        $subscriber = new DiscriminatorSubscriber;
        $map = $subscriber->buildMap();

        $this->assertTrue(is_array($map));
        $this->assertTrue(isset($map['abstract']));
        $this->assertTrue(isset($map['file']));
    }


    /**
     * Test that subscriber is attached to doctrine at runtime
     * @test
     */
    public function subscriberIsAttachedToDoctrine()
    {
        $connection = $this->em->getConnection();
        $eventManager = $connection->getEventManager();
        $listeners = $eventManager->getListeners(Events::loadClassMetadata);

        //Assert we have subscribers
        $this->assertFalse(empty($listeners));

        //Assert we have discriminator map subscriber
        $hasSubscriber = false;
        foreach($listeners as $index => $listener)
        {
            if($listener instanceof DiscriminatorSubscriber)
                $hasSubscriber = true;
        }

        $this->assertTrue($hasSubscriber);
    }


    /**
     * Test that we can inject settings discriminator map on doctrine
     * metadata loading.
     *
     * @test
     */
    public function canInjectDiscriminatorOnLoadClassmetadata()
    {
        $em = $this->em;
        $superclass = 'ShiftContentNew\FieldType\AbstractSettings';
        $meta = $em->getClassMetadata($superclass);

        //assert discriminator map set
        $subscriber = new DiscriminatorSubscriber;
        $map = $subscriber->buildMap();
        $this->assertEquals($map, $meta->discriminatorMap);

        //assert subclasses set
        $subClasses = array();
        foreach($map as $class)
        {
            if($class != $superclass)
                $subClasses[] = $class;
        }

        $this->assertEquals($subClasses, $meta->subClasses);
    }







}//class ends here