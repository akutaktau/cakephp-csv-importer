<?php
declare(strict_types=1);

namespace CsvImporter\Controller;

use Cake\Collection\Collection;
use CsvImporter\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\View\JsonView;
use Cake\Http\ServerRequest;
use Cake\ORM\Locator\LocatorAwareTrait;


/**
 * Imports Controller
 *
 * @method \CsvImporter\Model\Entity\Import[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ImportsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
       
    }

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

        $db = ConnectionManager::get('default');

        $collection = $db->getSchemaCollection();
        
        $tables = $collection->listTables(); 

        //pr($tables)
        $this->set(compact('tables'));
        $this->viewBuilder()->setOption('serialize', 'tables');
    } 

    public function fields($table) {
        $db = ConnectionManager::get('default');
       // echo $table; exit;
        $schema   = $db->getSchemaCollection();

        $describe = $schema->describe($table);

        $columns  = $describe->columns(); 
       

        $this->set(compact('columns'));
        $this->viewBuilder()->setOption('serialize', 'columns');
    }

    public function upload() {
        $data = $this->getRequest()->getData();

        $delimiter = ($data['delimiter'] == "t") ? "\t" : $data['delimiter'];

        $files = $this->getRequest()->getUploadedFiles();
       
        $fh = fopen($files['csv']->getStream()->getMetaData()['uri'], 'r');
        
        $fields = $data['field'];

        $csv_column = array_values($fields);
        $result = [];

        $skip = true;
        while (($line = fgetcsv($fh, 0, $delimiter)) !== false) {
            // do stuff
            if(!$skip) {
                $row = [];
                for($i=0;$i <= count($line);$i++){
                    if(in_array($i,$csv_column)) {
                        $key = array_search($i,$fields);
                        echo gettype($key);
                        $cols[$key] = $line[$i];
                        //$row = array_merge($row,$cols);
                    }
                   
                }
                $row = $cols;
               
                if(!empty($row)) 
                    array_push($result,$row);
                
            }

            $skip = false;
            
        }

        $tables = $this->getTableLocator()->get(ucfirst($data['tables']));
      
        $csvArray = $tables->newEntities($result); 
        
        try {
            $tables->saveManyOrFail($csvArray);
            $this->Flash->success('CSV Imported');

            return $this->redirect('/csv-importer/imports');
        }
        catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
           pr($e->getMessage());
        }
        
        
        exit;
    }
    
}
