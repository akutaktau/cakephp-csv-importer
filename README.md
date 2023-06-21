# CsvImporter plugin for CakePHP

## Not yet supported unit testing. Still in development

## Pre-requisites
1. Model to use with this plugin must be created
2. Model should follow Cakephp -> table naming convention

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require akutaktau/csv-importer
```

Load the plugin
```
bin/cake plugin load CsvImporter
```

Go to http://yourdomain/csv-importer/imports

##Use with your own UI
In the controller add
```
use CsvImporter\Controller\Component\ImportCsvComponent;
```

Then load the component
```
$this->loadComponent('CsvImporter.ImportCsv');
```

To get list of tables
```
$this->ImportCsv->getTables();
```

To get list of table column
```
$this->ImportCsv->getFields($table);
```

To upload and save csv to database
```
$this->ImportCsv->saveFile($data['field'],$data['tables'],$files['inputNameFromForm'],$delimiter);
```

$data['field'] is an array consist of fieldName from the table as the index and column number from the csv as the value.
```
$data['field'] = ['fieldName1' => 'columnNumber2','fieldName2' => 'columnNumber4']
```

$data['tables'] should have the table name.
```
$data['tables'] = 'tableName';
```

$files['inputNameFromForm'] should contain object from $this->getRequest()->getUploadedFiles()
```
$files = $this->getRequest()->getUploadedFiles();
```

$delimiter contain csv separator value
```
$delimiter = ',';
```