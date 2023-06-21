<?php
declare(strict_types=1);

namespace CsvImporter\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

/**
 * Import component
 */
class ImportCsvComponent extends Component
{
    public $schema;
    
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];

    /**
     * Function to get connection configuration to the database
     * 
     * @return object
     */
    
    private function getConnection($connection_type = 'default') {

        $db = ConnectionManager::get($connection_type);

        return $this->schema =  $db->getSchemaCollection();

    }

    /**
     * Set delimiter
     * 
     * @return string
     */

    private function setDelimeter($delimeter = ',') {
        $this->delimeter = ($delimeter == 't') ? "\t" : $delimeter;

        return $this->delimeter;
    }

    /**
     * Get list of tables
     * 
     * @return array<string,mix>
     */
    public function getTables() {

        $this->schema = $this->getConnection();

        return $this->schema->listTables();
    }

    /**
     * Get list of field
     * 
     * @return array<string,mix>
     */
    public function getFields($table) {

        $this->schema = $this->getConnection();
        
        $fields = $this->schema->describe($table);
        
        return $fields->columns(); 
    }

    /**
     * Save input to table
     * $tableColumn mapping database column to csv
     * $tableName table need to be update
     * $stream is getUploadedFiles object
     * $delimiter seperator used by csv file.
     * 
     * @return boolean
     */

    public function saveFile(array $tableColumn,$tableName,$stream,$delimiter = null) {
        $fh = fopen($stream->getStream()->getMetaData()['uri'], 'r');
        
        $delimiter = $this->setDelimeter($delimiter);

        $fields = $tableColumn;

        $csv_column = array_values($fields);
        $result = [];

        $skip = true;
        while (($line = fgetcsv($fh, 0, $delimiter)) !== false) {
            
            if(!$skip) {
                $row = [];
                for($i=0;$i <= count($line);$i++) {

                    if(in_array($i,$csv_column)) {
                        
                        $key = array_search($i,$fields);
                        $cols[$key] = $line[$i];
                        $row = array_merge($row,$cols);
                    }
                   
                }
               
                if(!empty($row)) 
                    array_push($result,$row);
                
            }

            $skip = false;
            
        }

        $tables = TableRegistry::getTableLocator()->get(ucfirst($tableName));
      
        $csvArray = $tables->newEntities($result); 
        
        try {

            $tables->saveManyOrFail($csvArray);
         
            return true;
        } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
           
            \Cake\Log\Log::debug($e->getMessage());

            return false;

        }
    }
}
