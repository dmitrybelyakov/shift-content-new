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
 * @subpackage  Controller
 */

/**
 * @namespace
 */
namespace ShiftContentNew\Api;
use Zend\View\Model\JsonModel;
use ShiftContentNew\Api\AbstractApiController;


/**
 * IndexController
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Controller
 */
class ApiController extends AbstractApiController
{
    /**
     * Delay
     * A sleeper sugar to delay api response.
     *
     * @param int $duration
     * @return void
     */
    protected function delay($duration = 2)
    {
        sleep($duration);
    }


    /**
     * List action
     * displays a list of items.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function listAction()
    {

        $this->delay();

        $item = array(
            'id' => '123',
            'title' => 'Me is an item',
            'description' => 'An item found by its unique id',
        );

        $items = [$item, $item, $item, $item, $item];

        return new JsonModel($items);
    }


    /**
     * Item action
     * Displays single item.
     * @return \Zend\View\Model\JsonModel
     */
    public function itemAction()
    {
        $this->delay();

        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        if(!$id)
            return $this->notFoundAction('No item with such id');


        $item = array(
            'id' => '123',
            'title' => 'Me is an item',
            'description' => 'An item found by its unique id',
        );

        return new JsonModel($item);

    }

} //class ends here