<?php
declare(strict_types=1);

namespace CsvImporter\Controller;

use CsvImporter\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\View\JsonView;
use Cake\Http\ServerRequest;
use Cake\ORM\Locator\LocatorAwareTrait;
use CsvImporter\Controller\Component\ImportCsvComponent;

/**
 * Imports Controller
 *
 * @method \CsvImporter\Model\Entity\Import[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ImportsController extends AppController
{
    public $schema;

    public function initialize():void {


        $this->loadComponent('CsvImporter.ImportCsv');

        parent::initialize();
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
       
    }

    /**
     * List all table in the database
     * 
     * @return json
     */
    public function viewClasses(): array
    {
         return [JsonView::class];
    }

    /**
     * List all table in the database
     * 
     * @return json
     */
    public function tables() {

        $tables = $this->ImportCsv->getTables();
       
        $this->set(compact('tables'));
        $this->viewBuilder()->setOption('serialize', 'tables');
    } 

    public function fields($table) {
      
        $columns = $this->ImportCsv->getFields($table);

        $this->set(compact('columns'));
        $this->viewBuilder()->setOption('serialize', 'columns');
    }

    /**
     * Save csv to the database
     * 
     * @return redirect
     */
    public function upload() {
      
        $data = $this->getRequest()->getData();

        $delimiter = $data['delimiter'];

        $files = $this->getRequest()->getUploadedFiles();
       
        $save = $this->ImportCsv->saveFile($data['field'],$data['tables'],$files['csv'],$delimiter);
     
        if($save) {
            $this->Flash->success(__('CSV Imported'));
            
        }
        else {
            $this->Flash->error(__('CSV Failed to import'));
        }

        return $this->redirect("/csv-importer/imports");
    }
    
}
