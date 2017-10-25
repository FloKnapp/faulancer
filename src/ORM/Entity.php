<?php

namespace Faulancer\ORM;

use Faulancer\Service\AbstractControllerService;
use Faulancer\ServiceLocator\ServiceLocator;
use \ORM\EntityManager;

/**
 * Class Entity
 *
 * @property int $id
 *
 * @category Faulancer\ORM
 * @package  Faulancer\ORM
 * @author   Florian Knapp <office@florianknapp.de>
 * @license  MIT License
 * @link     No link provided
 */
abstract class Entity extends \ORM\Entity
{
    /**
     * Return data as array
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    public function getDataAsArray()
    {
        return $this->getData();
    }

    /**
     * Save entity to database and add error handling for duplicate keys
     *
     * @param EntityManager $dbManager     The custom entity manager
     * @param bool          $redirectOnDup If redirect should be initiated
     *                                     on duplicate key error
     *
     * @return Entity|\ORM\Entity|bool
     *
     * @codeCoverageIgnore
     */
    public function save(EntityManager $dbManager = null, $redirectOnDup = false)
    {
        if ($dbManager !== null) {
            $this->setEntityManager($dbManager);
        }

        try {

            parent::save();

        } catch (\PDOException $e) {

            if ($e->getCode() === "23000") {

                if ($redirectOnDup) {

                    /** @var AbstractControllerService $controller */
                    $controller = ServiceLocator::instance()->get(
                        AbstractControllerService::class
                    );

                    $uri = $controller->getRequest()->getPath();

                    $controller->getSessionManager()->setFlashMessage(
                        'db.duplicate.key', 'Eintrag bereits vorhanden!'
                    );

                    $controller->redirect($uri);

                }

                return false;

            }

        }

        return $this;
    }

}