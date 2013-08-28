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
 * @subpackage  Doctrine
 */

/**
 * @namespace
 */
namespace ShiftContentNew\Doctrine;

use ShiftContentNew\Module;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs as MetadataArgs;

/**
 * Discriminator subscriber
 * Adds configured field settings entities to discriminator map to be able
 * to map them to content type fields for storage purpose.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Doctrine
 */
class DiscriminatorSubscriber implements EventSubscriber
{

    /**
     * Content module config
     * @var array
     */
    protected $config;


    /**
     * Base settings entity
     * The listener will be triggered for this one entity only.
     * @var string
     */
    protected $settingEntity = 'ShiftContentNew\FieldType\AbstractSettings';


    /**
     * Set config
     * Allows you to inject arbitrary config into listener.
     *
     * @param array $config
     * @return \ShiftContentNew\Doctrine\DiscriminatorListener
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }


    /**
     * Get config
     * Checks if we already have a config and returns that. Otherwise obtains
     * one from module bootstrap.
     * @return array
     */
    public function getConfig()
    {
        if(!$this->config)
            $this->setConfig(Module::getModuleConfig()->toArray());
        return $this->config;
    }


    /**
     * Get event subscribers
     * Implementation of subscriber interface: returns an array of events this
     * listener is subscribed to
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array('loadClassMetadata');
    }

    /**
     * Load class metadata
     * This gets triggered as soon as 'loadClassMetadata' is reached for
     * the given entity.
     *
     * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs
     * @return \Doctrine\ORM\Mapping\ClassMetadata
     */
    public function loadClassMetadata(MetadataArgs $eventArgs)
    {
        //check entity name
        $metadata = $eventArgs->getClassMetadata();
        $entityName = $metadata->getName();
        if($entityName != $this->settingEntity)
            return;

        //get map
        $map = $this->buildMap();
		$metadata->discriminatorMap = $map;

		/*
		 * IMPORTANT
		 * If we don't set the following metadata properties
		 * we won't get subclasses joined when querying for base entity
		 * as described in issue [SHIFT-129]
		 */
		foreach($map as $key => $class)
		{
			if($class == $this->settingEntity)
				$metadata->discriminatorValue = $key;
			else
				$metadata->subClasses[] = $class;
		}

        return $metadata;
    }


    /**
     * Build map
     * Parses config for content field types to build a map
     * of setting classes consumable by the listener.
     * @return array
     */
    public function buildMap()
    {
        $config = $this->getConfig();
        $fields = $config['contentFields'];

        $map['abstract'] = 'ShiftContentNew\FieldType\AbstractSettings';
        foreach($fields as $discriminator => $fieldTypeClass)
        {
            $fieldType = new $fieldTypeClass;
            $settingsClass = $fieldType->getSettingsClass();
            $settingsClass = ltrim($settingsClass, '\\');

            if(is_string($settingsClass) && !empty($settingsClass))
                $map[$discriminator] = $settingsClass;
        }

        return $map;
    }





} //class ends here