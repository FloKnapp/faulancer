<?php
/**
 * Class JsonResponseService | JsonResponseService.php
 * @package Faulancer\Service
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Service;

use Faulancer\Http\JsonResponse;
use Faulancer\ServiceLocator\ServiceInterface;

/**
 * Class JsonResponseService
 */
class JsonResponseService extends JsonResponse implements ServiceInterface {}