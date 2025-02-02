import re
import sys

class HornForm:
    def __init__(self, sentence):
        self.clause = []
        self.symbols = []
        self.head = ""
        self.conjuncts = []

        self.clause = re.split("(=>|&|\(|\)|~|\|\||<=>)",sentence)
        while("" in self.clause) : 
            self.clause.remove("") 
        while("(" in self.clause) : 
            self.clause.remove("(") 
        while(")" in self.clause) : 
            self.clause.remove(")") 
        
        if ('~' or '||' or '<=>') in self.clause:
            print("not horn form ", self.clause)
            sys.exit()
        if len(self.clause) == 1:
            self.head = self.clause[0]
        else:
            index = self.clause.index('=>')
            right = self.clause[index+1:]
            if (len(right) > 1):
                print("Error horn form format", self.clause)
                sys.exit()
            self.head = right[0]
            left = self.clause[:index]
            if (left[0] or left[-1]) == '&':
                print("Error horn form format", self.clause)
                sys.exit()
            for i in range(len(left)-1):
                if left[i] is left[i+1]: 
                    print("Error horn form format", self.clause)
                    sys.exit()
            for ele in left:
                if ele != '&':
                    self.conjuncts.append(ele)   
            self.symbols = self.conjuncts.copy()
        if self.head not in self.symbols:
            self.symbols.append(self.head)
