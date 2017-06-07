<?php
/**
 * Class Entity | Entity.php
 *
 * @package Faulancer\ORM
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\ORM;

use Faulancer\Service\AbstractControllerService;
use Faulancer\Service\DbService;
use Faulancer\ServiceLocator\ServiceLocator;
use \ORM\EntityManager;

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
        if ($dbManager !== null) {
            $this->setEntityManager($dbManager);
        }

        try {
            parent::save();
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