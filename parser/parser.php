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
            //if the posittion of # is 0 the return -> comment at the beginning of the line
            if (strpos($Line, "#") == 0) {
                return;
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
        
        parse($word); //, $word[1], $word[2], $word[3], $word[4]
        
        $inst_element = $xml->createElement("instruction");
        $inst_element->setAttribute("oreder", $order);
        $inst_element->setAttribute("opcode", $keyWord);
        $program_element->appendChild($inst_element);

        if ($word[1]){
            if(preg_match('/@/', $word[1], $match)){
                $cutType = substr($word[1],0, strpos($word[1], "@"));
                $value = substr($word[1],strpos($word[1], "@")+1);
                
                switch ($cutType) {
                    
                    case 'int':
                        $arg_element = $xml->createElement("arg1", $value);
                        $arg_element->setAttribute("type", "int");
                        $inst_element->appendChild($arg_element);
                        break;
                        
                    case 'bool':
                        $arg_element = $xml->createElement("arg1", strtolower($value));
                        $arg_element->setAttribute("type", "bool");
                        $inst_element->appendChild($arg_element);
                        break;
                        
                    case 'nil':
                        $arg_element = $xml->createElement("arg1", "nil");
                        $arg_element->setAttribute("type", "nil");
                        $inst_element->appendChild($arg_element);
                        break;
                        
                    case 'string':
                        $arg_element = $xml->createElement("arg1", $value);
                        $arg_element->setAttribute("type", "string");
                        $inst_element->appendChild($arg_element);
                        break;
                        
                    case ('GF'||'LF'||'TF'):
                        $arg_element = $xml->createElement("arg1", $word[1]);
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
                        $arg_element = $xml->createElement("arg1", "int");
                        $arg_element->setAttribute("type", "type");
                        $inst_element->appendChild($arg_element);
                        break;
                    case 'bool':
                        $arg_element = $xml->createElement("arg1", "bool");
                        $arg_element->setAttribute("type", "type");
                        $inst_element->appendChild($arg_element);
                        break;
                    case 'string':
                        $arg_element = $xml->createElement("arg1", "string");
                        $arg_element->setAttribute("type", "type");
                        $inst_element->appendChild($arg_element);
                        break;
                    default:
                        $arg_element = $xml->createElement("arg1", $word[1]);
                        $arg_element->setAttribute("type", "label");
                        $inst_element->appendChild($arg_element);
                        break;
                }
            }
        }
        
        
        if ($word[2]){
            if(preg_match('/@/', $word[2], $match)){
                $cutType = substr($word[2],0, strpos($word[2], "@"));
                $value = substr($word[2],strpos($word[2], "@")+1);
                
                switch ($cutType) {
                    
                    case 'int':
                        $arg_element = $xml->createElement("arg2", $value);
                        $arg_element->setAttribute("type", "int");
                        $inst_element->appendChild($arg_element);
                        break;
                        
                    case 'bool':
                        $arg_element = $xml->createElement("arg2", strtolower($value));
                        $arg_element->setAttribute("type", "bool");
                        $inst_element->appendChild($arg_element);
                        break;
                        
                    case 'nil':
                        $arg_element = $xml->createElement("arg2", "nil");
                        $arg_element->setAttribute("type", "nil");
                        $inst_element->appendChild($arg_element);
                        break;
                        
                    case 'string':
                        $arg_element = $xml->createElement("arg2", $value);
                        $arg_element->setAttribute("type", "string");
                        $inst_element->appendChild($arg_element);
                        break;
                        
                    case ('GF'||'LF'||'TF'):
                        $arg_element = $xml->createElement("arg2", $word[2]);
                        $arg_element->setAttribute("type", "var");
                        $inst_element->appendChild($arg_element);
                        break;
                    default:
                        // code...
                        break;
                }
            }
            else {
                switch ($word[2]) {
                    case 'int':
                        $arg_element = $xml->createElement("arg2", "int");
                        $arg_element->setAttribute("type", "type");
                        $inst_element->appendChild($arg_element);
                        break;
                    case 'bool':
                        $arg_element = $xml->createElement("arg2", "bool");
                        $arg_element->setAttribute("type", "type");
                        $inst_element->appendChild($arg_element);
                        break;
                    case 'string':
                        $arg_element = $xml->createElement("arg2", "string");
                        $arg_element->setAttribute("type", "type");
                        $inst_element->appendChild($arg_element);
                        break;
                    default:
                        $arg_element = $xml->createElement("arg2", $word[2]);
                        $arg_element->setAttribute("type", "label");
                        $inst_element->appendChild($arg_element);
                        break;
                }
            }
        }
        
        if ($word[3]){
            if(preg_match('/@/', $word[3], $match)){
                $cutType = substr($word[3],0, strpos($word[3], "@"));
                $value = substr($word[3],strpos($word[3], "@")+1);
                
                switch ($cutType) {
                    
                    case 'int':
                        $arg_element = $xml->createElement("arg3", $value);
                        $arg_element->setAttribute("type", "int");
                        $inst_element->appendChild($arg_element);
                        break;
                        
                    case 'bool':
                        $arg_element = $xml->createElement("arg3", strtolower($value));
                        $arg_element->setAttribute("type", "bool");
                        $inst_element->appendChild($arg_element);
                        break;
                        
                    case 'nil':
                        $arg_element = $xml->createElement("arg3", "nil");
                        $arg_element->setAttribute("type", "nil");
                        $inst_element->appendChild($arg_element);
                        break;
                        
                    case 'string':
                        $arg_element = $xml->createElement("arg3", $value);
                        $arg_element->setAttribute("type", "string");
                        $inst_element->appendChild($arg_element);
                        break;
                        
                    case ('GF'||'LF'||'TF'):
                        $arg_element = $xml->createElement("arg3", $word[3]);
                        $arg_element->setAttribute("type", "var");
                        $inst_element->appendChild($arg_element);
                        break;
                    default:
                        // code...
                        break;
                }
            }
            else {
                switch ($word[3]) {
                    case 'int':
                        $arg_element = $xml->createElement("arg3", "int");
                        $arg_element->setAttribute("type", "type");
                        $inst_element->appendChild($arg_element);
                        break;
                    case 'bool':
                        $arg_element = $xml->createElement("arg3", "bool");
                        $arg_element->setAttribute("type", "type");
                        $inst_element->appendChild($arg_element);
                        break;
                    case 'string':
                        $arg_element = $xml->createElement("arg3", "string");
                        $arg_element->setAttribute("type", "type");
                        $inst_element->appendChild($arg_element);
                        break;
                    default:
                        $arg_element = $xml->createElement("arg3", $word[3]);
                        $arg_element->setAttribute("type", "label");
                        $inst_element->appendChild($arg_element);
                        break;
                }
            }
        }
        
        
        
        
        $order++;
    }
    }
    $xml->formatOutput = TRUE;
    echo $xml->saveXML();
fclose($stdin);


function variable($string2match){
    if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $string2match, $match)){
        echo"hura match var \n";
    }
    else {
        echo "fnuk var \n";
        exit;
    }
}
/*
*checks syntax of symbols
*/
function symb($string2match){
    if(preg_match('/(LF|TF|GF|string|int|bool|nil)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $string2match, $match)){
        echo"hura match sym \n";
    }
    else {
        echo "fnuk sym \n";
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
            echo"hura match label \n";
        }
        else {
            echo "fnuk label \n";
            exit;
        }
}
}

/*
*checks if the number of args is correct
*/
function manyArg($string2match){
    if ($string2match) {
        echo "Error: Too many arg for this instruction!!\n";
        exit;
    }
    else {
        echo "Vsetko ok \n";
    }
}

/*
*checks syntax of type
*/
function type($string2match){
    if (preg_match('/(string|int|bool)/', $string2match, $match)) {
        echo"hura match type\n";
    }
    else {
        echo "fnuk type\n";
        exit;
    }
}


function parse($word){
    $keyWord = $word[0];
    
    
    
    switch (strtoupper($keyWord)) {
        case "MOVE":
            variable($word[1]);
            symb($word[2]);
            manyArg($word[3]);
            break;
            
            
        case "CREATEFRAME":
            manyArg($word[1]);
            break;
            
            
        case "PUSHFRAME":
            manyArg($word[1]);
            break;
            
            
        case "POPFRAME":
            manyArg($word[1]);
            break;
            
           
        case "DEFVAR":
            variable($word[1]);
            manyArg($word[2]);
            break;
            
            
        case "CALL":
            label($word[1]);
            manyArg($word[2]);
            break;
            
            
        case "RETURN":
            manyArg($word[1]);
            break;
            
            
        case "PUSHS":
            symb($word[1]);
            manyArg($word[2]);
            break;
            
            
        case "POPS":
            variable($word[1]);
            manyArg($word[2]);
            break;
            
            
        case "ADD":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "SUB":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "MUL":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "IDIV":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "LT":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "GT":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "EQ":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "\AND":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "OR":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "NOT":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "INT2CHAR":
            variable($word[1]);
            symb($word[2]);
            manyArg($word[3]);
            break;
            
            
        case "STR2INT":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "READ":
            variable($word[1]);
            type($word[2]);
            manyArg($word[3]);
            break;
            
            
        case "WRITE":
        symb($word[1]);
        manyArg($word[2]);
            break;
            
            
        case "CONCAT":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "STRLEN":
            variable($word[1]);
            symb($word[2]);
            manyArg($word[3]);
            break;
            
            
        case "GETCHAR":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "SETCHAR":
            variable($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "TYPE":
            variable($word[1]);
            symb($word[2]);
            manyArg($word[3]);
            break;
            
            
        case "LABEL":
            label($word[1]);
            manyArg($word[2]);
            break;
            
            
        case "JUMP":
            label($word[1]);
            manyArg($word[2]);
            break;
            
            
        case "JUMPIFEQ":
            label($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "JUMPIFNOTEQ":
            label($word[1]);
            symb($word[2]);
            symb($word[3]);
            manyArg($word[4]);
            break;
            
            
        case "EXIT":
        symb($word[1]);
        manyArg($word[2]);
            break;
            
            
        case "DPRINT":
        symb($word[1]);
        manyArg($word[2]);
            break;
            
            
        case "BREAK":
            manyArg($word[1]);
            break;
        
        
        default:
            echo "DEFAULT: Not instruction\n";
            break;
    }
    
}


?>