<!doctype html>
<?php
    /*
     * This application reads spreadsheets .csv, compares data
     * depending on selected field from user
     * and returns result
     *
     *   --by Simeon Simeonov 2015--
     */
 
 
//check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
    //upload file
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileOne"]["name"]);
    $target_file_two = $target_dir . basename($_FILES["fileTwo"]["name"]);
    
    //set validation flag if files submitted
    $uploadOk = 1;
 
 
    // Check if file already exists
    if (file_exists($target_file or $target_file_two)) {
        //echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileOne"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    if ($_FILES["fileTwo"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        //echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileOne"]["tmp_name"], $target_file) &&
                move_uploaded_file($_FILES["fileTwo"]["tmp_name"], $target_file_two)) {
 
                    echo "The file ". basename( $_FILES["fileOne"]["name"]). " and ". basename( $_FILES["fileTwo"]["name"]). "has been uploaded.";
        } else {
 
                    echo "Sorry, there was an error uploading your file.";
        }
    }//end if upload okay function
 
    //create array from file one
    function csv_to_array($target_file, $delimiter=',') {
        if(!file_exists($target_file) || !is_readable($target_file)) {
            return FALSE;
        }else{
        $header = NULL;
            $data = array();
            if (($handle = fopen($target_file, 'r')) !== FALSE) {
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE){
                    if(!$header){
                        $header = $row;
                    }else{
                        $data[] = array_combine($header, $row);
                    }
 
                }fclose($handle);
            }
        return $data;
        }
    }//end of function
 
 
    //set first csv file to array()
    $data = csv_to_array($target_file, $delimiter=',');
    //set second csv file to array()
    $data1 = csv_to_array($target_file_two, $delimiter=',');
    
    //set function to return matches
    $i = 0;
    foreach ( $data as $data_res ) {

        foreach ( $data1 as $data_res1 ) {

            if ($data_res1['ZIP'] == $data_res['ZIP'] ) {
              $results[$i]['zip'] = $data_res['ZIP'];
              $results[$i]['Name'] = $data_res['Name'];
              $results[$i]['Source'] = $data_res['Source'];
              $results[$i]['Match'] = $data_res1['ZIP']." ,". $data_res1['Name']." ,". $data_res1['Last Name']." ,".$data_res1['SALE_AMOUNT']." ,". $data_res1['OFFICE']." :: ";

            } else {

            }
        }//end of 2nd foreach
        
        $i++;
    }//end foreach statement
 
}//end if files submitted statement
 
 
 
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Submit report</title>
    <meta name="description" content="report comparison application">
    <meta name="author" content="Simeon Simeonov">
    <style>
    .form_div_two{
	width: 45%; 
	margin: auto; 
	border: 2px solid lightblue; 
	padding: 3%;
    }
    </style>
 
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.css">
    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.js"></script>
 
<script>
    $(document).ready( function () {
        $('#table_id').DataTable( {
            paging: false,
            //scrollY: 400
        } );
    });
</script>
 
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
</head>
 
<body>
    <div class="form_div">
        <form method="post" action="index.php" enctype="multipart/form-data">
            <p><label for="fileOne">Upload First File:</label>
                <input type="file" name="fileOne" id="fileOne"></p>
            <p><label for="fileTwo">Upload Second File:</label>
                <input type="file" name="fileTwo" id="fileTwo"></p>
 
            <p><input type="submit" id ='Submit'></p>
 
        </form>
    </div>
 
    <div class='form_div_two'>
        <?php if(isset($results)):
            if (count($results) > 0): ?>
                <table id="table_id" class="display">
                  <thead>
                    <tr>
                      <th><?php echo implode('</th><th>', array_keys(current($results))); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($results as $row): array_map('htmlentities', $row); ?>
                        <tr>
                            <td><?php echo implode('</td><td>', $row); ?></td>
                        </tr>
                    <?php endforeach; ?>
                  <tbody>
                </table>
        <?php endif; endif;?>
    </div>
</body>
</html>