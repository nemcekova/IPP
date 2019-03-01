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
        //echo $Line;
        
        
        parse($Line);
    }
    }

    
fclose($stdin);

function parse($lineString){
    
    $word = preg_split("/[\s]+/", $lineString);
    $keyWord = $word[0];
    
    switch ($keyWord) {
        case "MOVE":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match MOVE\n";
            }
            else {
                echo "fnuk MOVE\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 MOVE\n";
            }
            else {
                echo "fnuk MOVE\n";
                return;
            }
            
            break;
            
            
        case "CREATEFRAME":
            if ($word[1]) {
                echo "CHYBA CREATEFRAME!!\n";
                return;
            }
            else {
                echo "Vsetko ok CREATEFRAME\n";
            }    
            break;
            
            
        case "PUSHFRAME":
            if ($word[1]) {
                echo "CHYBA PUSHFRAME!!\n";
                return;
            }
            else {
                echo "Vsetko ok PUSHFRAME\n";
            }
            break;
            
            
        case "POPFRAME":
            if ($word[1]) {
                echo "CHYBA POPFRAME!!\n";
                return;
            }
            else {
                echo "Vsetko ok POPFRAME\n";
            }
        
            break;
            
            
        case "DEFVAR":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match DEFVAR\n";
            }
            else {
                echo "fnuk DEFVAR\n";
                return;
            }
            
            break;
            
            
        case "CALL":
            if(preg_match('/[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match CALL\n";
            }
            else {
                echo "fnuk CALL\n";
                return;
            }
            break;
            
            
        case "RETURN":
            if ($word[1]) {
                echo "CHYBA RETURN!!\n";
                return;
            }
            else {
                echo "Vsetko ok RETURN\n";
            }
            break;
            
            
        case "PUSHS":
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[1], $match)){
                echo"hura match PUSHS\n";
            }
            else {
                echo "fnuk PUSHS\n";
                return;
            }
            break;
            
            
        case "POPS":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match POPS\n";
            }
            else {
                echo "fnuk POPS\n";
                return;
            }
            break;
            
            
        case "ADD":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 ADD\n";
            }
            else {
                echo "fnuk1 ADD\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 ADD\n";
            }
            else {
                echo "fnuk2 ADD\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 ADD\n";
            }
            else {
                echo "fnuk3 ADD\n";
                return;
            }
            break;
            
            
        case "SUB":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 SUB\n";
            }
            else {
                echo "fnuk1 SUB\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 SUB\n";
            }
            else {
                echo "fnuk2 SUB\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 SUB\n";
            }
            else {
                echo "fnuk3 SUB\n";
                return;
            }
            break;
            
            
        case "MUL":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 MUL\n";
            }
            else {
                echo "fnuk1 MUL\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 MUL\n";
            }
            else {
                echo "fnuk2 MUL\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 MUL\n";
            }
            else {
                echo "fnuk3 MUL\n";
                return;
            }
            break;
            
            
        case "IDIV":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 IDIV\n";
            }
            else {
                echo "fnuk1 IDIV\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 IDIV\n";
            }
            else {
                echo "fnuk2 IDIV\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 IDIV\n";
            }
            else {
                echo "fnuk3 IDIV\n";
                return;
            }
            break;
            
            
        case "LT":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 LT\n";
            }
            else {
                echo "fnuk1 LT\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 LT\n";
            }
            else {
                echo "fnuk2 LT\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 LT\n";
            }
            else {
                echo "fnuk3 LT\n";
                return;
            }
            break;
            
            
        case "GT":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 GT\n";
            }
            else {
                echo "fnuk1 GT\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 GT\n";
            }
            else {
                echo "fnuk2 GT\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 GT\n";
            }
            else {
                echo "fnuk3 GT\n";
                return;
            }
            break;
            
            
        case "EQ":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 EQ\n";
            }
            else {
                echo "fnuk1 EQ\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 EQ\n";
            }
            else {
                echo "fnuk2 EQ\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 EQ\n";
            }
            else {
                echo "fnuk3 EQ\n";
                return;
            }
            break;
            
            
        case "\AND":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 AND\n";
            }
            else {
                echo "fnuk1 AND\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 AND\n";
            }
            else {
                echo "fnuk2 AND\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 AND\n";
            }
            else {
                echo "fnuk3 AND\n";
                return;
            }
            break;
            
            
        case "OR":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 OR\n";
            }
            else {
                echo "fnuk1 OR\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 OR\n";
            }
            else {
                echo "fnuk2 OR\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 OR\n";
            }
            else {
                echo "fnuk3 OR\n";
                return;
            }
            break;
            
            
        case "NOT":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 NOT\n";
            }
            else {
                echo "fnuk1 NOT\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 NOT\n";
            }
            else {
                echo "fnuk2 NOT\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 NOT\n";
            }
            else {
                echo "fnuk3 NOT\n";
                return;
            }
            break;
        case "INT2CHAR":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 INT2CHAR\n";
            }
            else {
                echo "fnuk1 INT2CHAR\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 INT2CHAR\n";
            }
            else {
                echo "fnuk2 INT2CHAR\n";
                return;
            }
            break;
            
            
        case "STR2INT":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 STR2INT\n";
            }
            else {
                echo "fnuk1 STR2INT\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 STR2INT\n";
            }
            else {
                echo "fnuk2 STR2INT\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 STR2INT\n";
            }
            else {
                echo "fnuk3 STR2INT\n";
                return;
            }
            break;
            
            
        case "READ":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 READ\n";
            }
            else {
                echo "fnuk1 READ\n";
                return;
            }
            if (preg_match('/(string|int|bool)/', $word[2], $match)) {
                echo"hura match2 READ\n";
            }
            else {
                echo "fnuk1 READ\n";
                return;
            }
            break;
            
            
        case "WRITE":
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[1], $match)){
                echo"hura match1 WRITE\n";
            }
            else {
                echo "fnuk1 WRITE\n";
                return;
            }
            break;
            
            
        case "CONCAT":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 CONCAT\n";
            }
            else {
                echo "fnuk1 CONCAT\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 CONCAT\n";
            }
            else {
                echo "fnuk2 CONCAT\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 CONCAT\n";
            }
            else {
                echo "fnuk3 CONCAT\n";
                return;
            }
            break;
            
            
        case "STRLEN":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 STRLEN\n";
            }
            else {
                echo "fnuk1 STRLEN\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 STRLEN\n";
            }
            else {
                echo "fnuk2 STRLEN\n";
                return;
            }
            break;
            
            
        case "GETCHAR":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 GETCHAR\n";
            }
            else {
                echo "fnuk1 GETCHAR\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 GETCHAR\n";
            }
            else {
                echo "fnuk2 GETCHAR\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 GETCHAR\n";
            }
            else {
                echo "fnuk3 GETCHAR\n";
                return;
            }
            break;
            
            
        case "SETCHAR":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 SETCHAR\n";
            }
            else {
                echo "fnuk1 SETCHAR\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 SETCHAR\n";
            }
            else {
                echo "fnuk2 SETCHAR\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 SETCHAR\n";
            }
            else {
                echo "fnuk3 SETCHAR\n";
                return;
            }
            break;
            
            
        case "TYPE":
            if(preg_match('/(LF|TF|GF)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 TYPE\n";
            }
            else {
                echo "fnuk1 TYPE\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 TYPE\n";
            }
            else {
                echo "fnuk2 TYPE\n";
                return;
            }
            break;
            
            
        case "LABEL":
            if(preg_match('/[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 LABEL\n";
            }
            else {
                echo "fnuk1 LABEL\n";
                return;
            }
            break;
            
            
        case "JUMP":
            if(preg_match('/[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 JUMP\n";
            }
            else {
                echo "fnuk1 JUMP\n";
                return;
            }
            break;
            
            
        case "JUMPIFEQ":
            if(preg_match('/[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 JUMPIFEQ\n";
            }
            else {
                echo "fnuk1 JUMPIFEQ\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 JUMPIFEQ\n";
            }
            else {
                echo "fnuk2 JUMPIFEQ\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 JUMPIFEQ\n";
            }
            else {
                echo "fnuk3 JUMPIFEQ\n";
                return;
            }
            break;
            
            
        case "JUMPIFNOTEQ":
            if(preg_match('/[a-zA-Z0-9\_\-\$\&\%\*\!\?]+/', $word[1], $match)){
                echo"hura match1 JUMPIFNOTEQ\n";
            }
            else {
                echo "fnuk1 JUMPIFNOTEQ\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[2], $match)){
                echo"hura match2 JUMPIFNOTEQ\n";
            }
            else {
                echo "fnuk2 JUMPIFNOTEQ\n";
                return;
            }
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[3], $match)){
                echo"hura match3 JUMPIFNOTEQ\n";
            }
            else {
                echo "fnuk3 JUMPIFNOTEQ\n";
                return;
            }
            break;
            
            
        case "EXIT":
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[1], $match)){
                echo"hura match1 EXIT\n";
            }
            else {
                echo "fnuk1 EXIT\n";
                return;
            }
            break;
            
            
        case "DPRINT":
            if(preg_match('/(LF|TF|GF|string|int|bool)@[a-zA-Z0-9\_\-\$\&\%\*\!\?]*/', $word[1], $match)){
                echo"hura match1 DPRINT\n";
            }
            else {
                echo "fnuk1 DPRINT\n";
                return;
            }
            break;
            
            
        case "BREAK":
            if ($word[1]) {
                echo "CHYBA BREAK!!\n";
                return;
            }
            else {
                echo "Vsetko ok BREAK\n";
            }
            break;
            break;
        
        
        default:
            // code...
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