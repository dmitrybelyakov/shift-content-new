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
 * @subpackage  Item
 */

/**
 * @namespace
 */
namespace ShiftContentNew\Item;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use ShiftUser\User\BaseUser as User;
use ShiftContentNew\Type\Type;
use Zend\View\Model\ViewModel;


/**
 * Content item metadata
 * This class encapsulates metadata common for all content items.
 *
 * @ORM\MappedSuperclass
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Item
 */
abstract class Meta
{
    /**
     * Item id
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * Item author
     *
     * @ORM\OneToOne(targetEntity="ShiftUser\User\BaseUser")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     * @var \ShiftUser\User
     */
    protected $author = null;

    /**
     * Parent content type
     * @ORM\OneToOne(targetEntity="ShiftContentNew\Type\Type")
     * @ORM\JoinColumn(name="contentTypeId", referencedColumnName="id")
     * @var \ShiftContentNew\Type\Type
     */
    protected $contentType = null;

    /**
     * Status
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    protected $status = 'draft';

    /**
     * Creation date
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    protected $created;

    /**
     * Publication date
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    protected $publicationDate;

    /**
     * Publication date
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    protected $lastUpdated;

    /**
     * Latitude
     * @ORM\Column(type="string", length=20, nullable=true)
     * @var \DateTime
     */
    protected $latitude;

    /**
     * Longitude
     * @ORM\Column(type="string", length=20, nullable=true)
     * @var \DateTime
     */
    protected $longitude;

    /**
     * Item title
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    protected $title;

    /**
     * Item slug
     * @ORM\Column(type="string", nullable=false, unique=true)
     * @var string
     */
    protected $slug;

    /**
     * Item tags
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $tags;

    /**
     * Item categories
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $categories;

    // ------------------------------------------------------------------------

    /*
     * Public API methods
     */


    /**
     * Create content entity
     * Initializes content entity. Optionally accepts data to be populated
     * at creation time
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data = array())
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->created = $now;
        $this->lastUpdated = $now;
        $this->tags = new ArrayCollection;
        $this->categories = new ArrayCollection;
        $this->fromArray($data);
    }

    /**
     * Implement me
     * Returns an array representation of entity.
     * @abstract
     */
    public function toArray()
    {
        $author = null;
        if(isset($this->author))
            $author = $this->author->toArray();

        $entityArray = array();
        $entityArray['id'] = $this->id;
        $entityArray['author'] = $author;
        $entityArray['status'] = $this->status;
        $entityArray['created'] = $this->created;
        $entityArray['publicationDate'] = $this->publicationDate;
        $entityArray['title'] = $this->title;
        $entityArray['slug'] = $this->slug;
        return $entityArray;
    }


    /**
     * From array
     * Populates entity from array data set.
     *
     * @param array $data
     * @return \ShiftContentNew\Item\Meta
     */
    public function fromArray(array $data)
    {
        foreach($data as $property => $value)
        {
            $methodName = 'set' . ucfirst($property);
            if(method_exists($this, $methodName))
                $this->$methodName($value);
        }

        return $this;
    }

    /**
     * Get view model
     * Composes view model with template and data for rendering.
     * @return \Zend\View\Model\ViewModel
     */
    public function getViewModel()
    {
        $vm = new ViewModel($this->toArray());
        return $vm;
    }


    /**
     * Get item id
     * @return int
     */
    public function getId()
    {
       return $this->id;
    }

    /**
     * Get author
     * @return \ShiftUser\User\BaseUser
     */
    public function getAuthor()
    {
       return $this->author;
    }


    /**
     * Set author
     * @param \ShiftUser\User\BaseUser $author
     * @return \ShiftContentNew\Item\Meta
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;
        return $this;
    }


    /**
     * Get content type
     * Returns parent content type for the item.
     * @return \ShiftContentNew\Type\Type | void
     */
    public function getContentType()
    {
        return $this->contentType;
    }


    /**
     * Set content type
     * Sets parent content type for the item.
     *
     * @param \ShiftContentNew\Type\Type $type
     * @return \ShiftContentNew\Item\Meta
     */
    public function setContentType(Type $type = null)
    {
        $this->contentType = $type;
        return $this;
    }


    /**
     * Get status
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * Mark draft
     * Sets item status to 'draft'
     * @return \ShiftContentNew\Item\Meta
     */
    public function markDraft()
    {
        $this->status = 'draft';
        return $this;
    }

    /**
     * Is draft
     * A boolean method to tell if item is a draft.
     * @return bool
     */
    public function isDraft()
    {
        return ($this->status == 'draft');
    }


    /**
     * Mark pending
     * Sets item status to 'pending'
     * @return \ShiftContentNew\Item\Meta
     */
    public function markPending()
    {
        $this->status = 'pending';
        return $this;
    }

    /**
     * Is pending
     * A boolean method to tell if item is pending approval.
     * @return bool
     */
    public function isPending()
    {
        return ($this->status == 'pending');
    }


    /**
     * Mark published
     * Sets item status to 'published'
     * @return \ShiftContentNew\Item\Meta
     */
    public function markPublished()
    {
        $this->status = 'published';
        return $this;
    }

    /**
     * Is published
     * A boolean method to tell if item is published.
     * @return bool
     */
    public function isPublished()
    {
        return ($this->status == 'published');
    }


    /**
     * Mark deleted
     * Sets item status to 'deleted'
     * @return \ShiftContentNew\Item\Meta
     */
    public function markDeleted()
    {
        $this->status = 'deleted';
        return $this;
    }

    /**
     * Is deleted
     * A boolean method to tell if item is deleted.
     * @return bool
     */
    public function isDeleted()
    {
        return ($this->status == 'deleted');
    }


    /**
     * Get creation date
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }


    /**
     * Get publication date
     * @return \DateTime
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }


    /**
     * Set publication date
     * @param \DateTime|string $publicationDate
     * @return \ShiftContentNew\Item\Meta
     */
    public function setPublicationDate($publicationDate)
    {
        if(is_string($publicationDate))
        {
            $publicationDate = new \DateTime(
                $publicationDate,
                new \DateTimeZone('UTC')
            );
        }

        //check timezone
        if($publicationDate instanceof \DateTime)
        {
            if('UTC' != $publicationDate->getTimezone()->getName())
                $publicationDate->setTimezone(new \DateTimeZone('UTC'));
        }

        $this->publicationDate = $publicationDate;
        return $this;
    }


    /**
     * Get last updated date
     * @return \DateTime
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }


    /**
     * Set last updated date
     * @param \DateTime|string $publicationDate
     * @return \ShiftContentNew\Item\Meta
     */
    public function setLastUpdated($updated)
    {
        if(is_string($updated))
            $updated = new \DateTime($updated, new \DateTimeZone('UTC'));

        //check timezone
        if($updated instanceof \DateTime)
        {
            if('UTC' != $updated->getTimezone()->getName())
                $updated->setTimezone(new \DateTimeZone('UTC'));
        }

        $this->lastUpdated = $updated;
        return $this;
    }


    /**
     * Touch
     * Updates last updated date setting it to now
     * @return \ShiftContentNew\Item\Meta
     */
    public function touch()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->lastUpdated = $now;
        return $this;
    }


    /**
     * Set latitude
     * Sets item creation location latitude.
     *
     * @param string $lat
     * @return \ShiftContentNew\Item\Meta
     */
    public function setLatitude($lat)
    {
        $this->latitude = $lat;
        return $this;
    }


    /**
     * Get latitude
     * Returns current item latitude.
     * @return string | null
     */
    public function getLatitude()
    {
        return $this->latitude;
    }


    /**
     * Set longitude
     * Sets item creation location longitude.
     *
     * @param string $lon
     * @return \ShiftContentNew\Item\Meta
     */
    public function setLongitude($lon)
    {
        $this->longitude = $lon;
        return $this;
    }


    /**
     * Get longitude
     * Returns current item longitude.
     * @return string | null
     */
    public function getLongitude()
    {
        return $this->longitude;
    }


    /**
     * Get geo point
     * Returns geo point in standard format of [latitude,longitude] is
     * those are set, otherwise returns.
     * @return string | null
     */
    public function getGeoPoint()
    {
        if(!$this->latitude || !$this->longitude)
            return;

        $geoPoint = $this->latitude . ',' . $this->longitude;
        return $geoPoint;
    }


    /**
     * Get item title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Set item title
     * @param string $title
     * @return \ShiftContentNew\Item\Meta
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }


    /**
     * Get item slug
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }


    /**
     * Set item slug
     * @param string $slug
     * @return \ShiftContentNew\Item\Meta
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }







} //class ends here