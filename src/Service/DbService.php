<?php

namespace Faulancer\Service;

use Faulancer\Exception\Exception;
use ORM\EntityFetcher;
use ORM\EntityManager;
use Faulancer\ORM\Entity;
use Faulancer\ServiceLocator\ServiceInterface;

/**
 * Class DbService
 *
 * @package Faulancer\Service
 * @author  Florian Knapp <office@florianknapp.de>
 */
class DbService implements ServiceInterface
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * ORM constructor.
     *
     * @param EntityManager $entityManager
     *
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
     *
     * @return Entity|EntityFetcher
     *
     * @throws Exception
     *
     * @codeCoverageIgnore Is covered by tflori/orm
     */
    public function fetch(string $entity, $primaryKey = null)
    {
        try {
            return $this->entityManager->fetch($entity, $primaryKey);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e->getFile());
        }
    }

}