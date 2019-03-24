#
#@proj Parser for IPPcode19
#@file parse.php
#@author Barbora Nemčeková <xnemce06@stud.fit.vutbr.cz>
#@date 17.02.2019
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

def createframe_func():
    global TF
    TF = {}
    
def pushframe_func():
    global LF, TF
    if(LF == ""):
        LF.append(TF)
    else:
        LF = []
        LF.append(TF)
    TF = None    
    
def popframe_func():
    global LF
    LF.pop()
    
    
def defvar_func(instruction): 
    variable = instruction[0].text
    name_split = variable.split("@", 1)
    var_frame = name_split[0]
    var_name = name_split[1]
    
    if (var_frame == "GF"):
        GF[var_name] = [instruction[0].attrib["type"], instruction[0].text]
        
    elif (var_frame == "TF"):
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
            

def move_func(instruction):
    to_var = instruction[0].text
    
    name_split = to_var.split("@", 1)
    var_frame = name_split[0]
    var_name = name_split[1]
    
    
    type = instruction[1].attrib["type"]
    if (type == "int" or type == "string" or type == "bool" or type == "nil"):
        data = instruction[1].text
    else:
        split_var = instruction[1].text.split("@", 1)
        frame = split_var[0]
        name = split_var[1]
        
        if (frame == "GF"):
            if (GF.get(name)):
                var = GF.get(name)
                type = var[0]
                data = var[1]
            else:
                sys.exit(54)
                
        elif (frame == "TF"):
            if (TF.get(name)):
                var = TF.get(name)
                type = var[0]
                data = var[1]
            else:
                sys.exit(54)        
                
        elif (frame == "LF"):
            for i in LF:        
                if (i.get(name)):
                    var = i.get(name)
                    type = var[0]
                    data = var[1]
                else:
                    sys.exit(54) 
    
    
    
    if(var_frame == "GF"):
        if (GF.get(var_name)):
            found_var = GF.get(var_name)
            found_var[0] = type
            found_var[1] = data
        else:
            sys.exit(54)
                
            
    elif(var_frame == "LF"):
        for item in LF:
            if (item.get(var_name)):
                found_var = item.get(var_name)
                found_var[0] = type
                found_var[1] = data
            else:
                sys.exit(54)
                    
    elif(var_frame == "TF"):
        if (TF.get(var_name)):
            found_var = TF.get(var_name)
            found_var[0] = type
            found_var[1] = data
        else:
            sys.exit(54)




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
        
print("LF:")
print(LF)
print("TF:")
print(TF)  
print("GF:") 
print(GF)           
        
