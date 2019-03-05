<?php
/*
*@proj Parser for IPPcode19
*@file parse.php
*@author Barbora Nemčeková <xnemce06@stud.fit.vutbr.cz>
*@date 17.02.2019
*/

//checks arguments
//Incorrect number of arguments
if ($argc > 2) {
    exit(10);
}
//Incorrect second argument
elseif (($argc == 2) && ($argv[1] != "--help")) {
    exit(10);
}
/*
*Help
*run as php7.3 parse.php --help
*/
elseif (($argc == 2) && ($argv[1] === "--help")) {
    echo "Help:
Parse.php is script filter. Script reads a source code in IPPcode19 language
from the stdin, does lexical and syntax analysis and prints XML represantation 
of the code to the std out.
    \n
Error return codes:
    21 - Incorrect or missing header in source code.
    22 - Incorrect or unknown instruction.
    23 - other lexical or syntax error in the source code.
    \n
How to run:
    php7.3 parse.php
or for help:
    php7.3 parse.php --help
Notice: no other arguments are allowed.\n";
}
/*
*run as php7.3 parse.php
*/
else{
    
    $stdin = fopen('php://stdin', 'r');
    //counter to check header just once
    $checkHeader = 0; 
    //counter for instruction order
    $order = 1;
    while($Line = fgets($stdin)){
        
        //
        if ($checkHeader == 0) {
            
            $Line = strtoupper($Line);
            
            //looks for a comment and cuts it
            if (preg_match('/#/', $Line, $match)) {
                $Line = substr($Line,0, strpos($Line, "#"));
            }
            
            //code starts with .IPPCODE19
            if (preg_match('/^.IPPCODE19/', $Line, $match)){
                $header = preg_split("/[\s]+/", $Line);
                
                //if includes anything else that language specif and comment - exit
                if ((count($header) > 1) && ($header[1] != '')) {
                        exit(21);    
                }
                
                //creates xml header
                else{
                $checkHeader++;
                
                $xml = new DomDocument("1.0", "UTF-8");
                $program_element = $xml->createElement("program");
                $program_element->setAttribute("language", "IPPcode19");
                $xml->appendChild($program_element);
                }
            }
            
            //if code doesnt start with language specif - exit
            else {
                exit(21);
            }
        }
        
        //if header is already checked, checks instructions
        else {
                    
            //looks for comment and cuts it off
            if (preg_match('/#/', $Line, $match)) {
                $cutString = substr($Line,0, strpos($Line, "#"));
                //comment at the beginning of the line - cotinues in while loop
                if (strpos($Line, "#") == 0) {
                    continue;
                }
                //splits the line into an array
                else {
                    $word = preg_split("/[\s]+/", $cutString);
                    $keyWord = $word[0];
                    }
            }
            //without comment
            else {
                $word = preg_split("/[\s]+/", $Line);
                $keyWord =strtoupper($word[0]);
            }
            
            //cuts empty string in an array (caused by new line)
            if ($emptyIndex = array_search('', $word)) {
                unset($word[$emptyIndex]);
            }
            
            //count words in array and call parsing function 
            $offset = count($word);
            parse($word, $offset);
            
            //create XML - instruction
            $inst_element = $xml->createElement("instruction");
            $inst_element->setAttribute("order", $order);
            $inst_element->setAttribute("opcode", $keyWord);
            $program_element->appendChild($inst_element);
            
            //loop creates xml represantation of arguments 
            for ($i = 1; $i < $offset; $i++){
                
                if($i == 1){
                    $arg = "arg1";
                    $var = $word[1];
                }
                elseif ($i == 2) {
                    $arg = "arg2";
                    $var = $word[2];
                }
                else {
                    $arg = "arg3";
                    $var = $word[3];
                }
                    
                //choose the right creation of the xml depending on the operand
                if(preg_match('/@/', $var, $match)){
                    $cutType = substr($var,0, strpos($var, "@"));
                    $value = substr($var,strpos($var, "@")+1);
                    
                    //switch for variables and constants
                    switch ($cutType) {
                        
                        case 'int':
                            $arg_element = $xml->createElement($arg, $value);
                            $arg_element->setAttribute("type", "int");
                            $inst_element->appendChild($arg_element);
                            break;
                            
                        case 'bool':
                            $arg_element = $xml->createElement($arg, strtolower($value));
                            $arg_element->setAttribute("type", "bool");
                            $inst_element->appendChild($arg_element);
                            break;
                            
                        case 'nil':
                            $arg_element = $xml->createElement($arg, "nil");
                            $arg_element->setAttribute("type", "nil");
                            $inst_element->appendChild($arg_element);
                            break;
                            
                        case 'string':
                            $value = replaceAmp($value);
                            $value = replaceProblemChars($value);
                            $arg_element = $xml->createElement($arg, $value);
                            $arg_element->setAttribute("type", "string");
                            $inst_element->appendChild($arg_element);
                            break;
                            
                        case ('GF'||'LF'||'TF'):
                            $var = replaceAmp($var);
                            $arg_element = $xml->createElement($arg, $var);
                            $arg_element->setAttribute("type", "var");
                            $inst_element->appendChild($arg_element);
                            break;
                        default:
                            echo "aaaaaaaaaaa\n";
                            break;
                    }
                }
                else {
                    //switch for types and labes
                    switch ($var) {
                        case 'int':
                            $arg_element = $xml->createElement($arg, "int");
                            $arg_element->setAttribute("type", "type");
                            $inst_element->appendChild($arg_element);
                            break;
                        case 'bool':
                            $arg_element = $xml->createElement($arg, "bool");
                            $arg_element->setAttribute("type", "type");
                            $inst_element->appendChild($arg_element);
                            break;
                        case 'string':
                            $arg_element = $xml->createElement($arg, "string");
                            $arg_element->setAttribute("type", "type");
                            $inst_element->appendChild($arg_element);
                            break;
                        default:
                            $var = replaceAmp($var);
                            $arg_element = $xml->createElement($arg, $var);
                            $arg_element->setAttribute("type", "label");
                            $inst_element->appendChild($arg_element);
                            break;
                    }
                }
            }            
            
            //order of instruction is incremented            
            $order++;
        }    
        }
        //formate and print xml after while loop for reading drom stdin ends
        $xml->formatOutput = TRUE;
        echo $xml->saveXML();
        
    fclose($stdin);

}
//function replaces & with "&amp;"
function replaceAmp($value){
    if (preg_match('/&/', $value, $match)) {
        $value = preg_replace('/&/', '&amp;', $value);
    }
    return $value;
}

//function replaces other problem chars: <, >, ", '
function replaceProblemChars($value){
    if (preg_match('/>/', $value, $match)) {
        $value = preg_replace('/>/', '&gt;', $value);
    }
    if (preg_match('/</', $value, $match)) {
        $value = preg_replace('/</', '&lt;', $value);
    }
    if (preg_match('/"/', $value, $match)) {
        $value = preg_replace('/\"/', '&quot;', $value);
    }
    if (preg_match('/\'/', $value, $match)) {
        $value = preg_replace('/\'/', '&apos;', $value);
    }
    return $value;
}

//function checks syntax of a variable
function variable($string2match){
    if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+$/', $string2match, $match)){
        return;
    }
    else {
        exit(23);
    }
}
/*
*checks syntax of symbols
*/
function symb($string2match){
    if (preg_match('/@/', $string2match, $match)){
        $cutType = substr($string2match,0, strpos($string2match, "@"));
        
        
        if ($cutType == "int") {
            if (preg_match('/int@[\-]*[0-9]+/', $string2match, $match)){
                return;
            }
            else {
                exit(23);
            }
        }    
        elseif ($cutType == "bool") {
            if (preg_match('/bool@(true|false)/', $string2match, $match)){
                return;
            }
            else {
                exit(23);
            }
        }
        elseif ($cutType == "nil") {
            if (preg_match('/nil@nil)/', $string2match, $match)){
                return;
            }
            else {
                exit(23);
            }
        }
        elseif ($cutType == "string") {
            if (preg_match('/string@([^\ \\\\#]|\\\\[0-9]{3})*$/', $string2match, $match)){
                return;
            }
            else {
                exit(23);
            }
        }
        elseif ($cutType == "LF"||"TF"||"GF") {
            if (preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+$/', $string2match, $match)){
                return;
            }
            else {
                exit(23);
            }
        }
    
    }
    else {
        exit(23);
    }
}
/*
*checks syntax of labels
*/
function label($string2match){
    if (preg_match('/@/', $string2match, $match)){
        exit(23);
    }
    else {
        if(preg_match('/[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $string2match, $match)){
            return;
        }
        else {
            exit(23);
        }
    }  
}

/*
*checks syntax of type
*/
function type($string2match){
    if (preg_match('/(string|int|bool)/', $string2match, $match)) {
        return;
    }
    else {
        exit(23);
    }
}

/*
*checks if the number of args is correct
*/
function manyArg(){
    echo "Error: Too many arg for this instruction!!\n";
    exit(23);
}
/*
*parsing function
*arguments: $word = line cut into array of strings
*           $offset = number of words on the line = instruction + operands
*/
function parse($word, $offset){
    $keyWord = $word[0];

    switch (strtoupper($keyWord)) {
        case "MOVE":
            variable($word[1]);
            symb($word[2]);
            if ($offset > 3){
                manyArg();
            }
            break;
            
          
        case "CREATEFRAME":
            if ($offset > 1){
                manyArg();
            }
            break;
            
            
        case "PUSHFRAME":
            if ($offset > 1){
                manyArg();
            }
            break;
            
            
        case "POPFRAME":
            if ($offset > 1){
                manyArg();
            }
            break;
            
           
        case "DEFVAR":
            variable($word[1]);
            if ($offset > 2){
                manyArg();
            }
            break;
            
            
        case "CALL":
            label($word[1]);
            if ($offset > 2){
                manyArg();
            }
            break;
            
            
        case "RETURN":
            if ($offset > 1){
                manyArg();
            }
            break;
            
            
        case "PUSHS":
            symb($word[1]);
            if ($offset > 2){
                manyArg();
            }
            break;
            
            
        case "POPS":
            variable($word[1]);
            if ($offset > 2){
                manyArg();
            }
            break;
            
            
        case "ADD":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "SUB":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "MUL":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "IDIV":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "LT":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "GT":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "EQ":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "\AND":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "OR":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "NOT":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "INT2CHAR":
            variable($word[1]);
            symb($word[2]);
            if ($offset > 3){
                manyArg();
            }
            break;
            
            
        case "STR2INT":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "READ":
            variable($word[1]);
            type($word[2]);
            if ($offset > 3){
                manyArg();
            }
            break;
            
            
        case "WRITE":
        symb($word[1]);
        if ($offset > 2){
            manyArg();
        }
            break;
            
            
        case "CONCAT":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "STRLEN":
            variable($word[1]);
            symb($word[2]);
            if ($offset > 3){
                manyArg();
            }
            break;
            
            
        case "GETCHAR":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "SETCHAR":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "TYPE":
            variable($word[1]);
            symb($word[2]);
            if ($offset > 3){
                manyArg();
            }
            break;
            
            
        case "LABEL":
            label($word[1]);
            if ($offset > 2){
                manyArg();
            }
            break;
            
            
        case "JUMP":
            label($word[1]);
            if ($offset > 2){
                manyArg();
            }
            break;
            
            
        case "JUMPIFEQ":
            label($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "JUMPIFNOTEQ":
            label($word[1]);
            symb($word[2]);
            symb($word[3]);
            if ($offset > 4){
                manyArg();
            }
            break;
            
            
        case "EXIT":
            symb($word[1]);
            if ($offset > 2){
                manyArg();
            }
            break;
            
            
        case "DPRINT":
            symb($word[1]);
            if ($offset > 2){
                manyArg();
            }
            break;
            
            
        case "BREAK":
            if ($offset > 1){
                manyArg();
            }
            break;
        
        
        default:
            exit(22);
            break;
    }
    

}

?>