<?php
/**
 * Class RequestService | RequestService.php
 * @package Faulancer\Service
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Service;

use Faulancer\Http\Request;
use Faulancer\ServiceLocator\ServiceInterface;

/**
 * Class RequestService
 */
class RequestService extends Request implements ServiceInterface {}