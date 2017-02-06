<?php
/**
 * Class DbServiceTest | DbServiceTest.php
 * @package Unit
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Test\Unit;

use Faulancer\Service\DbService;
use Faulancer\ServiceLocator\ServiceLocator;
use ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class DbServiceTest extends TestCase
{

    /** @var DbService */
    protected $dbService;

    public function setUp()
    {
        $this->dbService = ServiceLocator::instance()->get(DbService::class);
    }

    public function testGetManager()
    {
        $this->assertInstanceOf(EntityManager::class, $this->dbService->getManager());
    }

}