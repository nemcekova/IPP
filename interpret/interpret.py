#
#@proj Parser for IPPcode19
#@file parse.php
#@author Barbora Nemčeková <xnemce06@stud.fit.vutbr.cz>
#@date 24.03.2019
#
import sys
import xml.etree.ElementTree as ET
import re


#if sys.argv[1] == "--help":
#    print("---------HELP--------")


################################################################################
def check_var(text):
    text = text.split("@", 1)
    if (re.match('^([a-zA-Z]|[\_\-\$\&\%\*\!\?])([a-zA-Z0-9]|[\_\-\$\&\%\*\!\?])+$', text[1])):
        return
    
    else:
        sys.exit(32)


################################################################################
def check_int(text):
    if(re.match('^[+-]{0,1}[0-9]+$', text)):
        return
    else:
        sys.exit(32)
################################################################################
def check_string(text):
    if(re.match('^([^\ \\\\#]|\\\\[0-9]{3})*$', text)):
        RETURN
    else:
        sys.exit(32)
        
################################################################################
def check_bool(text):
    if(re.match('^(true|false)$', text)):
        return
    else:
        sys.exit(32)
################################################################################
def check_nil(text):
    if(re.match('^nil$', text)):
        return
    else:
        sys.exit(32)
        
################################################################################
def check_label(text):
    if(re.match('^([a-zA-Z]|[\_\-\$\&\%\*\!\?])([a-zA-Z0-9]|[\_\-\$\&\%\*\!\?])+$', text)):
        return
    else:
        sys.exit(32)
        
################################################################################
def check_type(text):
    if(re.match('^(string|int|bool)$', text)):
        return
    else:
        sys.exit
################################################################################
def check_symbol(type, text):
    if type ==  "var":
        text = text.split("@", 1)
        if (re.match('^([a-zA-Z]|[\_\-\$\&\%\*\!\?])([a-zA-Z0-9]|[\_\-\$\&\%\*\!\?])+$', text[1])):
            return
        else:
            sys.exit(32)
    elif type == "int":
        if(re.match('^[+-]{0,1}[0-9]+$', text)):
            return
        else:
            sys.exit(32)
    elif type == "string":
        if(re.match('^([^\ \\\\#]|\\\\[0-9]{3})*$', text)):
            RETURN
        else:
            sys.exit(32)
    elif type == "bool":
        if(re.match('^(true|false)$', text)):
            return
        else:
            sys.exit(32)
    elif type == "nil":
        if(re.match('^nil$', text)):
            return
        else:
            sys.exit(32)
            
################################################################################
#function for CREATEFRAME instruction
#inicialize temporary frame
def createframe_func():
    global TF
    TF = {}

################################################################################
#function for PUSHFRAME instruction
#push temporary frame into local frame    
def pushframe_func():
    global LF, TF
    if(LF == ""):
        LF.append(TF)
    else:
        LF = []
        LF.append(TF)
    TF = None    

################################################################################
#function pops from local frame    
def popframe_func():
    global LF
    LF.pop()

################################################################################    
#function for definition of variable    
def defvar_func(instruction): 
    variable = instruction[0].text
    name_split = variable.split("@", 1)
    var_frame = name_split[0]
    var_name = name_split[1]
    
    if (var_frame == "GF"):
        GF[var_name] = [instruction[0].attrib["type"], instruction[0].text]
        
    elif (var_frame == "TF"):
        #CREATEFRAME should be called before using TF variable
        if(TF == None):
            print("TF nie je definovane - nebol createframe")
            sys.exit(55)
        else:
            TF[var_name] = [instruction[0].attrib["type"], instruction[0].text]
                    
    elif (var_frame == "LF"):
        global LF
        if LF:
            LF[-1][var_name] = [instruction[0].attrib["type"], instruction[0].text]
        
        else:
            print("Nebol pushnuty ziadny frame")
            sys.exit(55)

################################################################################            
#function for MOVE instruction
def move_func(instruction):
    to_var = instruction[0].text
    name_split = to_var.split("@", 1)
    var_frame = name_split[0]
    var_name = name_split[1]
    
    
    type = instruction[1].attrib["type"]
    if (type == "int" or type == "string" or type == "bool" or type == "nil"):
        data = instruction[1].text
    else:
        var = instruction[1].text
        premenna = look_for_var(var)
        type = premenna[0]
        data = premenna[1]
        
    
    found_to_var = look_for_var(to_var)
    found_to_var[0] = type
    found_to_var[1] = data
    
################################################################################
#function looks for variable in frames
#@return found variable or exit 54
def look_for_var(var):
    split_var = var.split("@", 1)
    frame = split_var[0]
    name = split_var[1]
    
    if (frame == "GF"):
        if (GF.get(name)):
            ret_var = GF.get(name)
            return(ret_var)
        else:
            sys.exit(54)
    elif (frame == "TF"):
        if (TF.get(name)):
            ret_var = TF.get(name)
            return(ret_var)
        else:
            sys.exit(54)
    elif (frame == "LF"):
        for i in LF:        
            if (i.get(name)):
                ret_var = i.get(name)
                return(ret_var)
            else:
                sys.exit(54) 
                
################################################################################
#function for ADD instruction
def add_func(instruction):
    
    save_here = instruction[0].text
    sum = look_for_var(save_here)
    
    number1 = pom_math(instruction[1])
    number2 = pom_math(instruction[2])
    
    total = number1 + number2
    sum[0] = "int"
    sum[1] = total

################################################################################    
def sub_func(instruction):
        save_here = instruction[0].text
        sum = look_for_var(save_here)
        
        number1 = pom_math(instruction[1])
        number2 = pom_math(instruction[2])
        
        total = number1 - number2
        sum[0] = "int"
        sum[1] = total

################################################################################    
def mul_func(instruction):
    save_here = instruction[0].text
    sum = look_for_var(save_here)
    
    number1 = pom_math(instruction[1])
    number2 = pom_math(instruction[2])
    
    total = number1 * number2
    sum[0] = "int"
    sum[1] = total

################################################################################
def idiv_func(instruction):
    save_here = instruction[0].text
    sum = look_for_var(save_here)
    
    number1 = pom_math(instruction[1])
    number2 = pom_math(instruction[2])
    
    
    if(number2 == 0):
        sys.exit(57)
    
    
    total = number1 // number2
    sum[0] = "int"
    sum[1] = total    

################################################################################
#function helps take values from instructions or variables
def pom_math(instruction):
    if (instruction.attrib["type"] == "var"):
        var = instruction.text
        var_found = look_for_var(var)
        type = var_found[0]
        check_if_int(type)
        number = int(var_found[1])
        return(number)
    else:
        check_if_int(instruction.attrib["type"])
        number = int(instruction.text)
        return(number)

################################################################################
#function checks if variable is type int
def check_if_int(check):
    if (check == "int"):
        return
    else:
        sys.exit(53)

################################################################################
def compare_func(instruction, opcode):
    save_here = instruction[0].text
    sum = look_for_var(save_here)
    
    if (instruction[1].attrib["type"] == "var"):
        var1 = instruction[1].text
        var_found1 = look_for_var(var1)
        type1 = var_found1[0]
        compare1 = var_found1[1]
        
    else:
        type1 = instruction[1].attrib["type"]
        compare1 = instruction[1].text
    
    if (instruction[2].attrib["type"] == "var"):
        var2 = instruction[2].text
        var_found2 = look_for_var(var2)
        type2 = var_found2[0]
        compare2 = var_found2[1]
        
    else:
        type2 = instruction[2].attrib["type"]
        compare2 = instruction[2].text
    
    if (type1 != type2):
        sys.exit()
        
        
    if (type1 == "int" and type2 == "int"):
        compare1 = int(compare1)
        compare2 = int(compare2)
    
    if (opcode == "LT"):
        total = compare1 < compare2
        sum[0] = "bool"
        sum[1] = total
    elif (opcode == "GT"):
        total = compare1 > compare2
        sum[0] = "bool"
        sum[1] = total
    elif (opcode == "EQ"):
        total = compare1 == compare2
        sum[0] = "bool"
        sum[1] = total

################################################################################    
def logical_func(instruction, opcode):
    save_here = instruction[0].text
    sum = look_for_var(save_here)
    
    if (instruction[1].attrib["type"] == "var"):
        var1 = instruction[1].text
        var_found1 = look_for_var(var1)
        type1 = var_found1[0]
        compare1 = var_found1[1]
        
    else:
        type1 = instruction[1].attrib["type"]
        compare1 = instruction[1].text
    
    if (instruction[2].attrib["type"] == "var"):
        var2 = instruction[2].text
        var_found2 = look_for_var(var2)
        type2 = var_found2[0]
        compare2 = var_found2[1]
        
    else:
        type2 = instruction[2].attrib["type"]
        compare2 = instruction[2].text
        
    if(type1 != "bool" or type2 != "bool"):
        sys.exit(53)
    
    if(opcode == "AND"):
        sum[0] = "bool"
        if (compare1 == "true" and compare2 == "true"):
            sum[1] = "true"
        else:
            sum[1] = "false"
            
    elif(opcode == "OR"):
        sum[0] = "bool"
        if(compare1 == "false" and compare2 == "false"):
            sum[1] = "false"
        else:
            sum[1] = "true" 
            
            
################################################################################
def not_func(instruction):
    save_here = instruction[0].text
    sum = look_for_var(save_here)
    
    if (instruction[1].attrib["type"] == "var"):
        var = instruction[1].text
        var_found1 = look_for_var(var)
        type = var_found1[0]
        compare = var_found1[1]
        
    else:
        type = instruction[1].attrib["type"]
        compare = instruction[1].text
        
    if(type != "bool"):
        sys.exit(53)
    
    if (compare == "true"):
        sum[0] = "bool"
        sum[1] = "false"
    elif (compare == "false"):
        sum[0] = "bool"
        sum[1] = "true"
        
################################################################################
def getchar_func(instruction):
    save_here = instruction[0].text
    sum = look_for_var(save_here)
    
    if (instruction[1].attrib["type"] == "var"):
        var1 = instruction[1].text
        var_found1 = look_for_var(var1)
        type1 = var_found1[0]
        string = var_found1[1]
        
    else:
        type1 = instruction[1].attrib["type"]
        string = instruction[1].text
    
    if (instruction[2].attrib["type"] == "var"):
        var2 = instruction[2].text
        var_found2 = look_for_var(var2)
        type2 = var_found2[0]
        index = int(var_found2[1])
        
    else:
        type2 = instruction[2].attrib["type"]
        index = int(instruction[2].text)
        
    if (type1 != "string" and type2 != "int"):
        sys.exit(53)
        
    max_index = len(string)
    
    if((max_index -1 ) < index):
        sys.exit(58)
    
    sum[0] = "string"
    sum[1] = string[index]

################################################################################
def setchar_func(instruction):
    save_here = instruction[0].text
    string_change = look_for_var(save_here)
    
    if (instruction[1].attrib["type"] == "var"):
        var1 = instruction[1].text
        var_found1 = look_for_var(var1)
        type1 = var_found1[0]
        symbol = var_found1[1]
        
    else:
        type1 = instruction[1].attrib["type"]
        symbol = instruction[1].text
    
    if (instruction[2].attrib["type"] == "var"):
        var2 = instruction[2].text
        var_found2 = look_for_var(var2)
        type2 = var_found2[0]
        index = int(var_found2[1])
        
    else:
        type2 = instruction[2].attrib["type"]
        index = int(instruction[2].text)
         
    
    if((len(string_change[1]) -1 ) < index):
        sys.exit(58)
        
    if(len(symbol) > 1):
        symbol = symbol[0]
    
    string = list(string_change[1])
    string[index] = symbol
    
    string_change[1] = "".join(string)
    
################################################################################
def concat_func(instruction):
    save_here = instruction[0].text
    concat_sum = look_for_var(save_here)

    if (instruction[1].attrib["type"] == "var"):
        var1 = instruction[1].text
        var_found1 = look_for_var(var1)
        type1 = var_found1[0]
        string1 = var_found1[1]
        
    else:
        type1 = instruction[1].attrib["type"]
        string1 = instruction[1].text
    
    if (instruction[2].attrib["type"] == "var"):
        var2 = instruction[2].text
        var_found2 = look_for_var(var2)
        type2 = var_found2[0]
        string2 = var_found2[1]
        
    else:
        type2 = instruction[2].attrib["type"]
        string2 = instruction[2].text
        
    if(type1 != "string" or type2 != "string"):
        sys.exit(53)
    
    concat_sum[0] = "string"
    concat_sum[1] = string1 + string2


################################################################################
def strlen_func(instruction):
    lenght = look_for_var(instruction[0].text)
    
    if (instruction[1].attrib["type"] == "var"):
        var1 = instruction[1].text
        var_found1 = look_for_var(var1)
        type = var_found1[0]
        string = var_found1[1]
        
    else:
        type = instruction[1].attrib["type"]
        string = instruction[1].text
        
    print(string)
    lenght[0] = "int"
    lenght[1] = len(instruction[1].text)


################################################################################
def syntax_analysis(instruction):
    if instruction.tag == "instruction":
        if "order" not in instruction.attrib:
            sys.exit(32)
        if "opcode" not in instruction.attrib:
            sys.exit(32)
        code = instruction.attrib["opcode"]
        if code == "CREATEFRAME" or code == "PUSHFRAME" or code == "POPFRAME" or code == "BREAK" or code == "RETURN":
            if (len(instruction) != 0):
                sys.exit(32)
        
        #instructions with 3 arguments: var, symb, symb
        elif opcode in ["AND", "SUB", "MUL", "IDIV", "LT", "GT", "EQ", "AND", "OR", "NOT", "STRI2INT", "CONCAT", "GETCHAR", "SETCHAR"]:
            if len(instruction) != 3:
                sys.exit(32)
            #check firts argument
            if "type" not in instruction[0].attrib:
                sys.exit(32) 
            else:       
                if instruction[0].attrib["type"] != "var":
                    sys.exit(32)
                else:
                    check_var(instruction[0].text)
            #check second argument        
            if "type" not in instruction[1].attrib:
                sys.exit(32)  
            else:  
                if instruction[1].attrib["type"] in ["var", "int", "string", "bool", "nil"]:
                    check_symbol(instruction[1].attrib["type"], instruction[1].text)
                else:
                    sys.exit(32)
            #check third argument        
            if "type" not in instruction[2].attrib:
                sys.exit(32)
            else:    
                if instruction[2].attrib["type"] in ["var", "int", "string", "bool", "nil"]:
                    check_symbol(instruction[2].attrib["type"], instruction[1].text)
                else:
                    sys.exit(32)
        #instructions with 2 arguments: var, symb
        elif opcode in ["MOVE", "INT2CHAR", "STRLEN", "TYPE"]:
            if len(instruction) != 2:
                sys.exit(32)
            #check first argument
            if "type" not in instruction[0].attrib:
                sys.exit(32)
                if instruction[0].attrib["type"] != "var":
                    sys.exit(32)
                else:
                    check_var(instruction[0].text)
            #check second argument
            if "type" not in instruction[1].attrib:
                sys.exit(32)
            else:
                if instruction[1].attrib["type"] in ["var", "int", "string", "bool", "nil"]:
                    check_symbol(instruction[1].attrib["type"], instruction[1].text)
                else:
                    sys.exit(32)
        #instructions with one argument var
    elif opcode in ["DEFVAR", "POPS"]:
        if "type" not in instruction[0].attrib:
            sys.exit(32)
            if instruction[0].attrib["type"] != "var":
                sys.exit(32)
            else:
                check_var(instruction[0].text)

################################################################################

if (len(sys.argv) > 1):
    if (sys.argv[1] != "--help"):
        argument1 = sys.argv[1].split("=", 1)
        
        #if argv[1] is --source
        if (argument1[0] == "--source"):
            file_name = argument1[1]
            try:
                parse_file = open(file_name, "r")
            except OSError as error:
                sys.exit(11)
            
            
            #loads input data if argv[2] exists
            if(len(sys.argv) > 2):
                argument2 = sys.argv[2].split("=", 1)
                if (argument2[0] =="--input"):
                    input_file = argument2[1]
                    try:
                        read_file = open(input_file, r)
                    except OSError as error:
                        sys.exit(11)
                    
        #if argv[1] is --input            
        elif (argument1[0] =="--input"):
            input_file = argument1[1]
            try:
                read_file = open(input_file, r)
            except OSError as error:
                sys.exit(11)
            
            #loads source file if argv[2] exists
            if(len(sys.argv) > 2):
                argument2 = sys.argv[2].split("=", 1)
                if (argument2[0] == "--source"):
                    file_name = argument2[1]
                    try:
                        parse_file =open(file_name, "r")
                    except OSError as error:
                        sys.exit(11)
            else:
                parse_file = sys.stdin.read()
    else:
        print("-----------HELP-------------")
        sys.exit(0)
else:
    parse_file = sys.stdin.read()                
                
string = ""
for line in parse_file:
    string += line
#print(string)
try:
    program = ET.fromstring(string)
except ET.ParseError as error:
    sys.exit(31)


#syntax analysis
for instruction in program:
    syntax_analysis(instruction)
                
GF={}
TF=None
LF=[]



for instruction in program:
    opcode = instruction.attrib["opcode"]
    
    if opcode == "CREATEFRAME":
        createframe_func()    
    elif opcode == "PUSHFRAME":
        pushframe_func()    
    elif opcode == "POPFRAME":
        popframe_func()        
    elif opcode == "DEFVAR":
        defvar_func(instruction)
    elif opcode == "MOVE":
        move_func(instruction)
    elif opcode == "ADD":
        add_func(instruction)
    elif opcode == "SUB":
        sub_func(instruction)
    elif opcode == "MUL":
        mul_func(instruction)
    elif opcode == "IDIV":
        idiv_func(instruction)
    elif opcode == "LT" or opcode == "GT" or opcode == "EQ":
        compare_func(instruction, opcode)
    elif opcode == "AND" or opcode == "OR":
        logical_func(instruction, opcode)
    elif opcode == "NOT":
        not_func(instruction)
    elif opcode == "GETCHAR":
        getchar_func(instruction)
    elif opcode == "SETCHAR":
        setchar_func(instruction)
    elif opcode == "CONCAT":
        concat_func(instruction)
    elif opcode == "STRLEN":
        strlen_func(instruction)
        
print("LF:")
print(LF)
print("TF:")
print(TF)  
print("GF:") 
print(GF)           
                        
        

