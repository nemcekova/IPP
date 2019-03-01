<?php
/*
*@proj Parser for IPPcode19
*@file parser.php
*@author Barbora Nemčeková <xnemce06@stud.fit.vutbr.cz>
*@date 17.02.2019
*/

$stdin = fopen('php://stdin', 'r');
$counter = 0;
while($Line = fgets($stdin)){
    if ($counter == 0) {
        if (strcmp($Line, ".IPPcode19\n") !== 0) {
            printf("\nChyba specifikacia jazyka\n");
            return;
        }
        else {
            $counter++;
            echo $Line;
        }
    }
    else {
        parse($Line);
    }
    }

fclose($stdin);


function variable($string2match){
    if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $string2match, $match)){
        echo"hura match var \n";
    }
    else {
        echo "fnuk var \n";
        return;
    }
}

function symb($string2match){
    if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $string2match, $match)){
        echo"hura match sym \n";
    }
    else {
        echo "fnuk sym \n";
        return;
    }
}

function label($string2match){
    if(preg_match('/[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $string2match, $match)){
        echo"hura match label \n";
    }
    else {
        echo "fnuk label \n";
        return;
    }
}

function manyArg($string2match){
    if ($string2match) {
        echo "Error: Too many arg for this instruction!!\n";
        return;
    }
    else {
        echo "Vsetko ok \n";
    }
}

function type($string2match){
    if (preg_match('/(string|int|bool)/', $string2match, $match)) {
        echo"hura match type\n";
    }
    else {
        echo "fnuk type\n";
        return;
    }
}


function parse($lineString){
    
    $word = preg_split("/[\s]+/", $lineString);
    $keyWord = $word[0];
    
    switch ($keyWord) {
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
            echo "Not instruction\n";
            break;
    }
    
}
/*
$xml = new DomDocument("1.0", "UTF-8");
$program_element = $xml->createElement("Program");
$program_element->setAttribute("language", "IPPcode19");

$inst_element = $xml->createElement("instruction");
$inst_element->setAttribute("oreder","1");
$inst_element->setAttribute("opcode", "MOVE");
$program_element->appendChild($inst_element);

$arg_element = $xml->createElement("arg1");
$arg_element->setAttribute("type", "bool");
$inst_element->appendChild($arg_element);

$xml->appendChild($program_element);
$xml->formatOutput = TRUE;
echo $xml->saveXML();
*/
?>