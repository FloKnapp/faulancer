<?php
/**
 * Class Entity | Entity.php
 *
 * @package Faulancer\ORM
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\ORM;

use Faulancer\Http\Request;
use Faulancer\Service\AbstractControllerService;
use Faulancer\Service\DbService;
use Faulancer\Service\RequestService;
use Faulancer\ServiceLocator\ServiceLocator;
use ORM\EntityManager;

/**
 * Class Entity
 */
abstract class Entity extends \ORM\Entity {

    /**
     * @return array
     */
    public function getDataAsArray()
    {
        return $this->getData();
    }

    /**
     * @param EntityManager $dbManager
     * @return Entity|\ORM\Entity
     */
    public function save(EntityManager $dbManager = null)
    {
        /** @var DbService $db */
        $db = ServiceLocator::instance()->get(DbService::class);

        if ($dbManager !== null) {
            $manager = $dbManager;
        } else {
            $manager = $db->getManager();
        }

        try {
            return parent::save($manager);
        } catch (\PDOException $e) {

            if ($e->getCode() === "23000") {

                /** @var AbstractControllerService $controller */
                $controller = ServiceLocator::instance()->get(AbstractControllerService::class);
                $uri        = $controller->getRequest()->getUri();

                $controller->getSessionManager()->setFlashMessage('db.duplicate.key', 'Eintrag bereits vorhanden!');
                $controller->redirect($uri);

            }

        }

        return $this;
    }

}