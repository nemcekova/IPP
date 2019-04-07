<?php
/*
*@proj test frame for interpret.py and parse.php
*@file test.php
*@author Barbora Nemčeková <xnemce06@stud.fit.vutbr.cz>
*@date 05.04.2019
*/

$path_test = getcwd();
$parse_file = getcwd() . "/parse.php";
$int_file = getcwd() . "/interpret.py";
$only_parse = false;
$only_int = false;
$recursive = false;
$failed_tests = 0;
$succ_tests = 0;
$overal_tests = 0;

echo "<!DOCTYPE HTML>";
	echo "<html>";
	echo "<head>";
		echo "<meta charset=\"utf-8\">";
		echo "<meta name=\"viewport\" content=\"width=1920, initial-scale=1.0\">";
		echo "<title>IPP tests report</title>";
	echo "</head>";
	echo "<body>";
    
if (($argc == 2) && ($argv[1] === "--help")) {
    echo("----------------------------------Help:----------------------------------\n");
    exit();
}
if (($argc >= 2) && ($argv[1] !== "--help")){
    if (in_array("--parse-only",$argv) && in_array("--int-only", $argv)){
        exit(10);
    }
    if (in_array("--parse-only",$argv) && in_array("--int-script", $argv)){
        exit(10);
    }
    if (in_array("--parse-script",$argv) && in_array("--int-only", $argv)){
        exit(10);
    }
    for($i = 0; $i<$argc; $i++){
        //tests are in the path directory
        if($found_dir = strstr($argv[$i], "--directory")){
            $path_test = substr($found_dir, strpos($found_dir, "=")+1);
        }

        //parse file
        elseif($found_parse = strstr($argv[$i], "--parse-script")){
            $parse_file = substr($found_parse, strpos($found_parse, "=")+1);
        }

        //interpret file
        elseif($found_int = strstr($argv[$i], "--parse-script")){
            $int_file = substr($found_int, strpos($found_int, "=")+1);
        }

        //recursive search for tests
        elseif($argv[$i] === "--recursive"){
            //prehladava rekurzivne vsetky podadresare
            $recursive = true;
        }
        
        elseif($argv[$i] === "--parse-only"){
            $only_parse = true;
        }
        elseif($argv[$i] === "--int-only"){
            $only_int = true;
        }
    }
}


Directory($path_test);


/*
*Function checks if the path exists and for every test source calls the test function
*/
function Directory($path){
    global $src, $recursive, $only_parse, $only_int;
        $src = false;
    //checks and open directory
    if(is_dir($path)){
        $dir = opendir($path);
        
        //reads directory
        while(false !== ($entry = readdir($dir))){
            $extension = pathinfo($entry, PATHINFO_EXTENSION);
            $test_name = basename($entry, $extension);
            //print($path . "\n");
            
                //test source
                if($extension === 'src'){
                    //print($entry . "\n");
                    $src = true;
                    run_test($path, $test_name);
                }
                elseif($recursive === true){
                    if (is_dir($path . "/" . $entry) && $entry !== "." && $entry !== ".."){
                        if($only_parse == true){
                            if(strstr($entry, "parse")){
                                Directory($path . "/" . $entry);
                            }
                        }
                        elseif ($only_int == true) {
                            if(strstr($entry, "int")){
                                Directory($path . "/" . $entry);
                            }
                        }
                        else{
                            if(strstr($entry, "both")){
                                Directory($path . "/" . $entry);
                            }
                        }
                    }
                }
        }
    }
    //not a real path or directory
    else{
        echo "fuck\n" . $path . "\n";
    }
}


function run_test($path, $test_name){
    global $parse_file, $int_file, $only_int, $only_parse, $failed_tests, $succ_tests, $overal_tests;
    $in = false;
    $out = false;
    $rc = false;
    $dir = opendir($path);
    
    while(false !== ($entry = readdir($dir))){
        $extension = pathinfo($entry, PATHINFO_EXTENSION);
        $name = basename($entry, $extension);
        
        if($extension === 'in' && $test_name === $name){
            $in = true;
        }

        elseif($extension === 'out' && $test_name === $name){
            $out = true;
        }
        elseif($extension === 'rc' && $test_name === $name){
            $rc = true;
        }
    }
        
    if ($in === false){
        shell_exec('touch ' . $path . "/" . $test_name . 'in');
    }
    if ($out === false){
        shell_exec('touch ' . $path . "/" . $test_name . 'out');
    }
    if ($rc === false){
        shell_exec('touch ' . $path . "/" . $test_name . 'rc');
        shell_exec('echo "0" >> ' . $path . "/" . $test_name . 'rc');
    }
    
    $return_var = 0; 
    $out = "";   
    shell_exec('touch ./xml');
    shell_exec('touch ./output');
    
    if($only_parse === true){
        exec('php7.3 ' . $parse_file . ' < ' . $path . "/" . $test_name . 'src > ./xml', $out, $return_var);
        $rc = exec('cat ' . $path . "/" . $test_name . 'rc');
        $overal_tests +=1;
        if ($return_var != $rc){
            /*
            *FAILED
            */
            $failed_tests += 1;
			echo"<table >
			    	  <tr>
			          <td>" . $test_name . ": </td><td>FAILED</td><td>Incorrect return value</td>
			          </tr>
				</table>";
        }
        else{
            exec('java -jar /pub/courses/ipp/jexamxml/jexamxml.jar ./xml ' . $path . "/" . $test_name . 'out /pub/courses/ipp/jexamxml/options', $out, $return_var);
            if($return_var != 0){
                /*
                *FAILED
                */
                //print("failed\n");
                $failed_tests += 1;
            }
            else{
                /*
                *OK
                */
                //print("ok\n");
                $succ_tests += 1;
				echo"<table >
				    	  <tr>
				          <td>" . $test_name . ": </td><td>OK</td>
				          </tr>
					</table>";
            }
        }
    }
    
    elseif ($only_int === true) {
        exec('python3.6 ' . $int_file . ' --source=' . $path . "/" . $test_name . 'src < ' . $path . "/" . $test_name . 'in > ./output', $out, $return_var);
        $rc = exec('cat ' . $path . "/" . $test_name . 'rc');
        $overal_tests +=1;
        if($rc != $return_var){
            /*
            *FAILED
            */
            $failed_tests += 1;
			echo"<table >
			    	  <tr>
			          <td>" . $test_name . ": </td><td>FAILED</td><td>Incorrect return value</td>
			          </tr>
				</table>";
            //print("$rc\n");
            //print("$return_var\n");
        }
        else{
            exec('diff ./output ' . $path . "/" . $test_name . 'out', $out, $rc);
            if($rc != 0){
                /*
                *FAILED
                */
                $failed_tests += 1;
                //print("falied\n");
            }
            else{
                /*
                *OK
                */
                $succ_tests +=1;
				echo"<table >
				    	  <tr>
				          <td>" . $test_name . ": </td><td>OK</td>
				          </tr>
					</table>";
                //print("ok\n");
            }
        }
    }
    
    else{
        exec('php7.3 ' . $parse_file . ' < ' . $path . "/" . $test_name . 'src > ./xml', $out, $return_var);
        $rc = exec('cat ' . $path . "/" . $test_name . 'rc');
        $overal_tests += 1;
        //if exit code parse is not 0
        if($return_var !== 0){
            
            //if it doeasnt match return code in file .rc
            if($return_var !== $rc){
                /*
                *FAILD
                */
                $failed_tests += 1;
                //print("Failed parse\n");
            }
            //if it does match tests are correct
            else{
                /*
                *OK CONTINUE
                */
                //print("parse ok\n");
            }
        }
        else{
            //print("parse ok\n");
        }

        exec('python3.6 ' . $int_file . ' --source=./xml < ' . $path . "/" . $test_name . 'in > ./output', $out, $return_var);

        if($return_var != $rc){
            /*
            *FAILED
            */
            $failed_tests += 1;
			echo"<table >
			    	  <tr>
			          <td>" . $test_name . ": </td><td>FAILED</td><td>Incorrect return value " . $return_var ."</td>
			          </tr>
				</table>";
            //print("faled test interpret wrong value\n");
        }
        else{
            exec('diff ./output ' . $path . "/" . $test_name . 'out', $out, $rc);
            if($rc != 0){
                /*
                *FAILED
                */
                $failed_tests += 1;
            }
            //print("preslo to ok\n");
            $succ_tests += 1;
            /*
            *OK
            */
			echo"<table >
			    	  <tr>
			          <td>" . $test_name . ": </td><td>OK</td>
			          </tr>
				</table>";
        }
            
    }
    
    
}
    
    
	echo"<div class=\"Summary\">
		<h3>Summary</h3>
		<table >
		<font size=\"30\" color=\"black\">
		    	  <tr>
		          <td>Executed tests:</td><td>" . $overal_tests . "</td>
		          </tr>
		          <tr>
		            <td>Passed:</td><td>$succ_tests</td>
		          </tr>
		          <tr>
		             <td>Failed:</td><td>$failed_tests</td>
		           </tr>  
			</font>         
	        </table>
	</div>";
	echo "</body>";
    echo "</html>";
//function 
?>