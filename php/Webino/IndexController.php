<?php
/**
 * Webino
 *
 * PHP version 5.3
 *
 * LICENSE: This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt. It is also available through the
 * world-wide-web at this URL: http://www.webino.org/license/
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@webino.org
 * so we can send you a copy immediately.
 *
 * @category   Webino
 * @package    Index
 * @subpackage Controller
 * @author     Peter Bačinský <peter@bacinsky.sk>
 * @copyright  2012 Peter Bačinský (http://www.bacinsky.sk/)
 * @license    http://www.webino.org/license/ New BSD License
 * @version    GIT: $Id$
 * @link       http://pear.webino.org/index/
 */

use Zend_Controller_Action_Exception as ActionException;

/**
 * Webino index controller
 *
 * example of options:
 *
 * - packagesListCommand = 'pear list -c webino'
 *
 * @category   Webino
 * @package    Index
 * @subpackage Controller
 * @author     Peter Bačinský <peter@bacinsky.sk>
 * @copyright  2012 Peter Bačinský (http://www.bacinsky.sk/)
 * @license    http://www.webino.org/license/ New BSD License
 * @version    Release: @@PACKAGE_VERSION@@
 * @link       http://pear.webino.org/index/
 */
class Webino_IndexController
    extends Zend_Controller_Action
{
    /**
     * Name of command argument name
     */
    const COMMAND_KEYNAME = 'packagesListCommand';
    
    /**
     * Name of view variable to set packages array
     */
    const VIEW_VARNAME = 'packages';

    /**
     * Package name
     */
    const PACKAGE_NAME_PROPERTY = 'name';

    /**
     * Package version
     */
    const PACKAGE_VERSION_PROPERTY = 'version';

    /**
     * Package stability
     */
    const PACKAGE_STATE_PROPERTY = 'state';

    /**
     * Assign installed PEAR packages data to view
     */
    public function indexAction()
    {
        $command = $this->getInvokeArg(self::COMMAND_KEYNAME);

        if (!$command) {
            throw new ActionException(
                sprintf('Missing argument "%s"', self::COMMAND_KEYNAME)
            );
        }

        $list = explode(PHP_EOL, shell_exec($command));

        array_shift($list);
        array_shift($list);
        array_shift($list);
        array_pop($list);

        $data = array();

        foreach ($list as $line) {
            list($name, $version, $state) = explode(
                ' ', preg_replace('~ +~', ' ', $line)
            );

            $data[] = array(
                self::PACKAGE_NAME_PROPERTY    => trim($name),
                self::PACKAGE_VERSION_PROPERTY => trim($version),
                self::PACKAGE_STATE_PROPERTY   => trim($state),
            );
        }

        $this->view->assign(self::VIEW_VARNAME, $data);
    }
}
