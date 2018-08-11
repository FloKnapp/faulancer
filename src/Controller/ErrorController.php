<?php

namespace Faulancer\Controller;

use Faulancer\Http\Request;
use Faulancer\Http\Response;
use Faulancer\Service\Config;

/**
 * Class ErrorAbstractController
 *
 * @category ErrorController
 * @package  Faulancer\Controller
 * @author   Florian Knapp <office@florianknapp.de>
 * @license  MIT License
 * @link     not provided
 */
class ErrorController extends Controller
{

    /**
     * The exception object
     *
     * @var \Exception
     */
    private $_exception;

    /**
     * ErrorController constructor.
     *
     * @param Request    $request The request object
     * @param \Exception $e       The given exception
     *
     * @codeCoverageIgnore
     */
    public function __construct(Request $request, $e)
    {
        parent::__construct($request);
        $this->_exception = $e;
    }

    /**
     * Decide if debug output or 404 page should be rendered
     *
     * @return Response
     *
     * @codeCoverageIgnore
     */
    public function displayError()
    {
        ob_end_clean();

        if (defined('APPLICATION_ENV') && APPLICATION_ENV !== 'production') {
            return $this->_renderDebugPage();
        }

        echo "Hallo";

        return $this->_renderNotFoundPage();

    }

    /**
     * Render the debug output
     *
     * @return Response
     *
     * @codeCoverageIgnore
     */
    private function _renderDebugPage()
    {
        $this->getView()->addStylesheet('/core/css/main.css');
        $this->getView()->addStylesheet('/core/css/darcula.css');
        $this->getView()->addScript('/core/js/namespace.js');
        $this->getView()->addScript('/core/js/engine.js');
        $this->getView()->addScript('/core/js/highlight.pack.js');
        $this->getView()->setTemplatePath(__DIR__ . '/../../template');

        $raiser = [
            'function'=> $this->_exception->getTrace()[0]['function'] ?? 'unknown',
            'message' => $this->_exception->getMessage(),
            'type'    => $this->_exception->getCode(),
            'file'    => $this->_exception->getFile(),
            'line'    => $this->_exception->getLine()
        ];

        $trace  = $this->_exception->getTrace();

        if (isset($trace[0]['line']) && $trace[0]['line'] !== $raiser['line']) {
            array_unshift($trace, $raiser);
        } else {
            array_shift($trace);
            array_unshift($trace, $raiser);
        }

        return $this->render(
            '/debug.phtml',
            [
                'exception' => $this->_exception,
                'trace' => $trace
            ]
        );
    }

    /**
     * Render a 404 page
     *
     * @return Response
     */
    private function _renderNotFoundPage()
    {
        /** @var Config $config */
        $config = $errorController = $this->getServiceLocator()->get(Config::class);

        $errorController = $config->get('customErrorController');

        if ($errorController) {
            return (new $errorController($this->request))->notFoundAction();
        }

        die();
    }

}