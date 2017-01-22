<?php
/**
 * Class FormError | FormError.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Session\SessionManager;
use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class FormError
 */
class FormError extends AbstractViewHelper
{

    /** @var ViewController */
    protected $view;

    /** @var string */
    protected $field;

    /**
     * Initializing form error helper
     *
     * @param ViewController $view
     * @param string         $field
     * @return $this
     */
    public function __invoke(ViewController $view, string $field)
    {
        $this->view  = $view;
        $this->field = $field;

        return $this;
    }

    /**
     * Get an error for specific field
     *
     * @return string
     */
    public function get()
    {
        $error = SessionManager::instance()->getFlashbagError($this->field);

        $result = '';

        if (!empty($error)) {

            $result = '<div class="form-error ' . $this->field . '">';

            foreach ($error as $err) {
                $result .= '<span>' . $this->view->translate($err['message']) . '</span>';
            }

            $result .= '</div>';

        }

        return $result;
    }

    /**
     * Check if a field name has an error
     *
     * @return bool
     */
    public function has()
    {
        return SessionManager::instance()->hasFlashbagErrorsKey($this->field);
    }

}