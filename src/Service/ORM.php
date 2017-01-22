<?php
/**
 * Class ORM | ORM.php
 * @package Faulancer\Service
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Service;

use ORM\EntityFetcher;
use ORM\EntityManager;
use Faulancer\ORM\Entity;
use Faulancer\ServiceLocator\ServiceInterface;

/**
 * Class ORM
 */
class ORM implements ServiceInterface
{

    /** @var EntityManager */
    protected $entityManager;

    /**
     * ORM constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Return the EntityFetcher
     *
     * @param string       $entity
     * @param integer|null $primaryKey
     * @return Entity|EntityFetcher
     * @codeCoverageIgnore
     */
    public function fetch(string $entity, $primaryKey = null)
    {
        return $this->entityManager->fetch($entity, $primaryKey);
    }

    /**
     * Save an entity
     *
     * @param Entity $entity
     * @codeCoverageIgnore
     */
    public function save(Entity $entity)
    {
        $entity->save($this->entityManager);
    }

}