<?php
/**
 * Class SessionManagerService | SessionManagerService.php
 * @package Faulancer\Service
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Service;

use Faulancer\ServiceLocator\ServiceInterface;
use Faulancer\Session\SessionManager;

class SessionManagerService extends SessionManager implements ServiceInterface {}