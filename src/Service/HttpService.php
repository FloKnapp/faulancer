<?php
/**
 * Class HttpService | HttpService.php
 * @package Faulancer\Service
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Service;

use Faulancer\Http\Http;
use Faulancer\ServiceLocator\ServiceInterface;

/**
 * Class HttpService
 */
class HttpService extends Http implements ServiceInterface {}