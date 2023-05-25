<?php
declare(strict_types=1);

namespace CsvImporter\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use CsvImporter\Controller\ImportsController;

/**
 * CsvImporter\Controller\ImportsController Test Case
 *
 * @uses \CsvImporter\Controller\ImportsController
 */
class ImportsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'plugin.CsvImporter.Imports',
    ];

    /**
     * Test index method
     *
     * @return void
     * @uses \CsvImporter\Controller\ImportsController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \CsvImporter\Controller\ImportsController::view()
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \CsvImporter\Controller\ImportsController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \CsvImporter\Controller\ImportsController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \CsvImporter\Controller\ImportsController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
