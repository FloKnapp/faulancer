<?php

namespace Faulancer\Service;

use ORM\EntityFetcher;
use ORM\EntityManager;
use Faulancer\ORM\Entity;
use Faulancer\ServiceLocator\ServiceInterface;
use ORM\Exception\IncompletePrimaryKey;
use ORM\Exception\NoEntity;

/**
 * Class DbService
 *
 * @package Faulancer\Service
 * @author  Florian Knapp <office@florianknapp.de>
 */
class DbService implements ServiceInterface
{

    /** @var EntityManager */
    protected $entityManager;

    /**
     * ORM constructor.
     *
     * @param EntityManager $entityManager
     * @codeCoverageIgnore
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->entityManager;
    }

    /**
     * Return the EntityFetcher
     *
     * @param string       $entity
     * @param integer|null $primaryKey
     * @return Entity|EntityFetcher
     *
     * @throws IncompletePrimaryKey
     * @throws NoEntity
     *
     * @codeCoverageIgnore Is covered by tflori/orm
     */
    public function fetch(string $entity, $primaryKey = null)
    {
        return $this->entityManager->fetch($entity, $primaryKey);
    }

    /**
     * Save an entity
     *
     * @param Entity $entity
     * @codeCoverageIgnore Is covered by tflori/orm
     */
    public function save(Entity $entity)
    {
        $entity->save($this->entityManager);
    }

}