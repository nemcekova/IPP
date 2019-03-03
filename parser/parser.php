<?php
/*
*@proj Parser for IPPcode19
*@file parser.php
*@author Barbora Nemčeková <xnemce06@stud.fit.vutbr.cz>
*@date 17.02.2019
*/

$stdin = fopen('php://stdin', 'r');
$counter = 0;
$order = 1;
while($Line = fgets($stdin)){
    $comment = false;
    if ($counter == 0) {
        //if (strcmp($Line, ".IPPcode19\n") != 0) {
        $Line = strtoupper($Line);
        if (preg_match('/\.IPPCODE19/', $Line, $match)){
            $counter++;
            
            $xml = new DomDocument("1.0", "UTF-8");
            $program_element = $xml->createElement("Program");
            $program_element->setAttribute("language", "IPPcode19");
            $xml->appendChild($program_element);
            
        }
        else {
            printf("\nChyba specifikacia jazyka\n");
            return;
        }
    }
    else {
        
        
        //looks for comment and cuts it off
        if (preg_match('/#/', $Line, $match)) {
            $cutString = substr($Line,0, strpos($Line, "#"));
            
            //if the posittion of # is 0 -> comment at the beginning of the line
            if (strpos($Line, "#") == 0) {
                $comment = true;
            }
            else {
                $word = preg_split("/[\s]+/", $cutString);
                $keyWord = $word[0];
                }
        }
        else {
            $word = preg_split("/[\s]+/", $Line);
            $keyWord =strtoupper($word[0]);
        }
        
        $offset = count($word) -1;
        parse($word, $offset);
        
        if ($comment == false){
        
            $inst_element = $xml->createElement("instruction");
            $inst_element->setAttribute("order", $order);
            $inst_element->setAttribute("opcode", $keyWord);
            $program_element->appendChild($inst_element);

            for ($i = 1; $i <= $offset; $i++){
                if($i == 1){
                    $arg = "arg1";
                }
                elseif ($i == 2) {
                    $arg = "arg2";
                }
                else {
                    $arg = "arg3";
                }
                    
                
                if(preg_match('/@/', $word[1], $match)){
                    $cutType = substr($word[1],0, strpos($word[1], "@"));
                    $value = substr($word[1],strpos($word[1], "@")+1);
                    
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
                            $arg_element = $xml->createElement($arg, $value);
                            $arg_element->setAttribute("type", "string");
                            $inst_element->appendChild($arg_element);
                            break;
                            
                        case ('GF'||'LF'||'TF'):
                            $arg_element = $xml->createElement($arg, $word[1]);
                            $arg_element->setAttribute("type", "var");
                            $inst_element->appendChild($arg_element);
                            break;
                        default:
                            // code...
                            break;
                    }
                }
                else {
                    switch ($word[1]) {
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
                            $arg_element = $xml->createElement($arg, $word[1]);
                            $arg_element->setAttribute("type", "label");
                            $inst_element->appendChild($arg_element);
                            break;
                    }
                }
            }

            
        }


        else {
            
        }
        
        $order++;
        
    }
    }
    $xml->formatOutput = TRUE;
    echo $xml->saveXML();
    
fclose($stdin);


function variable($string2match){
    if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $string2match, $match)){
        return;
    }
    else {
        exit;
    }
}
/*
*checks syntax of symbols
*/
function symb($string2match){
    if(preg_match('/(LF|TF|GF|string|int|bool|nil)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $string2match, $match)){
        return;
    }
    else {
        exit;
    }
}

/*
*checks syntax of labels
*/
function label($string2match){
    if (preg_match('/@/', $string2match, $match)){
        exit;
    }
    else {
        if(preg_match('/[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $string2match, $match)){
            return;
        }
        else {
            exit;
        }
}
}

/*
*checks if the number of args is correct
*/
function manyArg(){
        echo "Error: Too many arg for this instruction!!\n";
        exit;
}

/*
*checks syntax of type
*/
function type($string2match){
    if (preg_match('/(string|int|bool)/', $string2match, $match)) {
        return;
    }
    else {
        exit;
    }
}


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
            echo "DEFAULT: Not instruction\n";
            break;
    }
    
}


?>