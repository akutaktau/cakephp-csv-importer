<?php 
/**
 * 
 * Index.php
 * To render page upload the csv file  
 * 
 */
?>

<?php echo $this->Form->create(null,['type' => 'file','url' => '/csv-importer/imports/upload']);?>

    <!--Upload -->
    <label><?php __('Upload :');?></label>
    <input type="file" name="csv" />
    <!--Upload -->
    <label><?php __('CSV Delimiter :');?></label>
    <input type="text" name="delimiter" /><small><?php __('Use t for tab delimiter');?></small>
    <!--Get tables -->
    <label><?php __('Db table :');?></label>
    <select id="tables" name="tables" />
    </select>
    <span id="mapping"></span>
    <input type="submit">
<?php echo $this->Form->end();?>

<script>
    //Get list of tables from the database
    let dropdown = document.getElementById('tables');
    dropdown.length = 0;

    //create dropdown default options
    let defaultOption = document.createElement('option');
    defaultOption.text  = <?= __('Choose a table')?>;
    defaultOption.value = ""; 
    dropdown.add(defaultOption);
    dropdown.selectedIndex = 0;


    const url = '<?php echo $this->Url->buildFromPath('CsvImporter.Imports::tables'); ?>';

    fetch(
        url,{
            method: "GET",
            headers: {'Accept': 'application/json'} //set to receive json only
       }
    )  
    .then(  
        function(response) {  
        if (response.status !== 200) {  
            console.warn('<?php __("Looks like there was a problem. Status Code: ");?>' + 
            response.status);  
            return;  
        }

        // Examine the text in the response  
        response.json().then(function(data) {  
            let option;
        
            for (let i = 0; i < data.length; i++) {
            option = document.createElement('option');
            option.text = data[i];
            option.value = data[i];
            dropdown.add(option);
            }    
        });  
        }  
    )  
    .catch(function(err) {  
        console.error('Fetch Error -', err);  
    });
    

    //generate table for mapping db columns and csv columns
    document.querySelector('#tables').addEventListener('change',function(){
        
        //clear mapping span table
        document.getElementById('mapping').innerHTML = "";
        if(this.value != "") {
            
            let theTable = document.getElementById('mapping');
            let table = document.createElement('table');
            let thead = document.createElement('thead');
            let tbody = document.createElement('tbody');
            
            var tr = document.createElement('tr');
            var th = document.createElement('th');
            th.textContent = 'Field Name';
            
            tr.appendChild(th);
            var th = document.createElement('th');
            th.textContent = 'CSV column number';
            tr.appendChild(th);
            thead.appendChild(tr);
            table.appendChild(thead);
            //theTable.appendChild(table);

            
            const uri = '<?php echo $this->Url->build('/csv-importer/imports/fields/'); ?>'+ this.value;
            console.log(uri);
            fetch(
                uri,{
                    method: "GET",
                    headers: {'Accept': 'application/json'}
            }
            )  
            .then(  
                function(response) {  
                if (response.status !== 200) {  
                    console.warn('Looks like there was a problem. Status Code: ' + 
                    response.status);  
                    return;  
                }

                // Examine the text in the response  
                response.json().then(function(data) {  
                    let row;
                    let tbody = document.createElement('tbody');
                   // let table = document.createElement('table');
                    for (let i = 0; i < data.length; i++) {
                        
                        row = document.createElement('tr');
                        var td = document.createElement('td');
                        td.textContent = data[i];
                        row.appendChild(td);
                        var td = document.createElement('td');
                        td.innerHTML = '<input type="text" name="field['+data[i]+']" />';
                        row.appendChild(td);
                        tbody.appendChild(row);
                       
                    }  
                    table.appendChild(tbody);
                }); 
                
                theTable.appendChild(table); 
                }  
            )  
            .catch(function(err) {  
                console.error('Fetch Error -', err);  
            });
        }
        

    });
            
</script>