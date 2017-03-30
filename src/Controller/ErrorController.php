<?php
/**
 * Class ErrorController | ErrorController.php
 *
 * @package Faulancer\Controller
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Controller;

use Faulancer\Exception\Exception;
use Faulancer\Http\Request;

/**
 * Class ErrorController
 */
class ErrorController extends Controller
{

    private $exception;

    /**
     * ErrorController constructor.
     * @param Request   $request
     * @param \Exception $e
     * @codeCoverageIgnore
     */
    public function __construct(Request $request, \Exception $e)
    {
        parent::__construct($request);
        $this->exception = $e;
    }

    /**
     * @return \Faulancer\Http\Response
     * @codeCoverageIgnore
     */
    public function displayError()
    {
        ob_end_clean();

        if (getenv('APPLICATION_ENV') === 'development') {
            return $this->renderDebugPage();
        }

        return $this->renderNotFoundPage();

    }

    /**
     * @return \Faulancer\Http\Response
     * @codeCoverageIgnore
     */
    private function renderDebugPage()
    {
        $this->getView()->addStylesheet('/core/css/main.css');
        $this->getView()->addScript('/core/js/namespace.js');
        $this->getView()->addScript('/core/js/engine.js');
        $this->getView()->setTemplatePath(__DIR__ . '/../../template');

        $trace  = $this->exception->getTrace();
        $raiser = array_splice($trace, 0, 1);

        return $this->render('/debug.phtml', ['exception' => $this->exception, 'raiser' => $raiser, 'trace' => $trace]);
    }

    /**
     * @codeCoverageIgnore
     */
    private function renderNotFoundPage()
    {

    }

}