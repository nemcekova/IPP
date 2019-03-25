#
#@proj Parser for IPPcode19
#@file parse.php
#@author Barbora Nemčeková <xnemce06@stud.fit.vutbr.cz>
#@date 24.03.2019
#
import sys
import xml.etree.ElementTree as ET


#if sys.argv[1] == "--help":
#    print("---------HELP--------")

parse_file = open("ipp.xml", "r")
string = ""
for line in parse_file:
    string += line
#print(string)
program = ET.fromstring(string)

GF={}
TF=None
LF=[]

#function for CREATEFRAME instruction
#inicialize temporary frame
def createframe_func():
    global TF
    TF = {}

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

#function pops from local frame    
def popframe_func():
    global LF
    LF.pop()
    
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

#function for ADD instruction
def add_func(instruction):
    
    save_here = instruction[0].text
    sum = look_for_var(save_here)
    
    number1 = pom_math(instruction[1])
    number2 = pom_math(instruction[2])
    
    total = number1 + number2
    sum[0] = "int"
    sum[1] = total
    
def sub_func(instruction):
        save_here = instruction[0].text
        sum = look_for_var(save_here)
        
        number1 = pom_math(instruction[1])
        number2 = pom_math(instruction[2])
        
        total = number1 - number2
        sum[0] = "int"
        sum[1] = total
    
def mul_func(instruction):
    save_here = instruction[0].text
    sum = look_for_var(save_here)
    
    number1 = pom_math(instruction[1])
    number2 = pom_math(instruction[2])
    
    total = number1 * number2
    sum[0] = "int"
    sum[1] = total

def idiv_func(instruction):
    save_here = instruction[0].text
    sum = look_for_var(save_here)
    
    number1 = pom_math(instruction[1])
    number2 = pom_math(instruction[2])
    
    if(number2 == "0"):
        exit(57)
    
    total = number1 // number2
    sum[0] = "int"
    sum[1] = total    

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

#function checks if variable is type int
def check_if_int(check):
    if (check == "int"):
        return
    else:
        exit(53)

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
        
print("LF:")
print(LF)
print("TF:")
print(TF)  
print("GF:") 
print(GF)           
        
