<?php
namespace ar\core;
/**
 * ArPHP A Strong Performence PHP FrameWork ! You Should Have.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Core.base
 * @author   yc <ycassnr@gmail.com>
 * @license  http://www.arphp.org/licence MIT Licence
 * @version  GIT: 1: coding-standard-tutorial.xml,v 1.0 2014-5-01 18:16:25 cweiske Exp $
 * @link     http://www.arphp.org
 */

/**
 * application
 *
 * default hash comment :
 *
 * <code>
 *  # This is a hash comment, which is prohibited.
 *  $hello = 'hello';
 * </code>
 *
 * @category ArPHP
 * @package  Core.base
 * @author   yc <ycassnr@gmail.com>
 * @license  http://www.arphp.org/licence MIT Licence
 * @version  Release: @package_version@
 * @link     http://www.arphp.org
 */
class ApplicationWeb extends Application
{
    // route container
    public $route = array();

    /**
     * start function.
     *
     * @return void
     */
    public function start()
    {
        if (AR_DEBUG && !AR_AS_CMD) :
            \ar\core\comp('ext.out')->deBug('[APP_WEB_START]');
        endif;
        if (AR_AUTO_START_SESSION && ini_get('session.auto_start') == 0) :
            if (!AR_AS_WEB_CLI) :
                session_start();
            endif;
        endif;

        $this->processRequest();

    }

    /**
     * process.
     *
     * @return void
     */
    public function processRequest()
    {
        $this->runController(Ar::getConfig('requestRoute'));

    }

    /**
     * default controller.
     *
     * @param string $route route.
     *
     * @return mixed
     */
    public function runController($route)
    {
        if (AR_DEBUG && !AR_AS_CMD) :
            \ar\core\comp('ext.out')->deBug('[CONTROLLER_RUN]');
        endif;

        Ar::setConfig('requestRoute', $route);

        if (empty($route['a_c'])) :
            $c = 'Index';
        else :
            $c = ucfirst($route['a_c']);
        endif;

        $this->route['a_c'] = $c;
        // $class = $c . 'Controller';
        $class = $c;

        if (AR_DEBUG && !AR_AS_CMD) :
            \ar\core\comp('ext.out')->deBug('|CONTROLLER_EXEC:'. $class .'|');
        endif;

        $class = 'ctl\\' . $route['a_m'] . '\\' . $class;

        if (class_exists($class)) :
            $this->_c = new $class;
            $action = ($a = empty($route['a_a']) ? AR_DEFAULT_ACTION : $route['a_a']);

            switch ($action) {
                case 'init':
                    throw new \ar\core\Exception('Action: ' . $action . ' not found');
                    break;
                // case '':
                default:
                    # code...
                    break;
            }

            if (!method_exists($this->_c, $action)) :
                throw new \ar\core\Exception('Action: ' . $action . ' not found');
            endif;

            $this->_c->init();
            $this->route['a_a'] = $a;

            if (is_callable(array($this->_c, $action))) :
                try {
                    if (AR_DEBUG && !AR_AS_CMD) :
                        \ar\core\comp('ext.out')->deBug('|ACTION_RUN:' . $action . '|');
                    endif;
                    $this->_c->$action();
                    if (AR_AS_OUTER_FRAME) :
                        exit;
                    endif;
                } catch (\ar\core\Exception $e) {
                    if (!AR_AS_OUTER_FRAME) :
                        throw new \ar\core\Exception($e->getMessage());
                    endif;
                }
            else :
                if (!AR_AS_OUTER_FRAME) :
                    throw new \ar\core\Exception('Action ' . $action . ' not found');
                endif;
            endif;
        else :
            throw new \ar\core\Exception('Controller:' . $class . ' not found');
        endif;

    }

}
