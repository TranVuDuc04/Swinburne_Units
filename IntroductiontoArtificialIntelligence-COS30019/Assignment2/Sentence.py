import re
import sys

class Sentence:
    def __init__(self, sentence):
        self.symbols = []
        self.root = []
        self.atomic = {}

        original = re.split("(=>|&|\(|\)|~|\|\||<=>)",sentence)
        while "" in original:
            original.remove("")
        self.original = original.copy()
        symbols = re.split("=>|&|\(|\)|~|\|\||<=>",sentence)
        while "" in symbols:
            symbols.remove("")
        self.symbols = list(set(symbols))

        self.root = self.__parse(original)

    def __parse(self, sentence):
        while '(' in sentence:
            left_index = sentence.index('(')
            left_count = 1
            right_count = 0

            right_index = 0
            for i in range(left_index+1, len(sentence)):
                if sentence[i] == '(':
                    left_count+=1
                elif sentence[i] == ')':
                    right_count+=1
                if left_count == right_count:
                    right_index = i
                    break
            if (right_index == 0):
                print("Incorrect brackets format in sentence: ", sentence)
                sys.exit()
            section = sentence[left_index+1:right_index]
            section = self.__parse(section)
            if len(section) == 1:
                sentence[left_index] = section[0]
                del sentence[left_index+1:right_index+1]
            else:
                print("Incorrect senction format: ", section)
                sys.exit()
        while '~' in sentence:
            index = sentence.index('~')
            sentence = self.__add_atom(index, sentence, '~')
        while ('&' or '||') in sentence:
            if '&' in sentence:
                index = sentence.index('&')
            if '||' in sentence:
                if sentence.index('||') < index:
                    index = sentence.index('||')
            sentence = self.__add_atom(index, sentence, '&||')
        while ('=>') in sentence:
            index = sentence.index('=>')
            sentence = self.__add_atom(index, sentence, '=>')
        while ('<=>') in sentence:
            index = sentence.index('<=>')
            sentence = self.__add_atom(index, sentence, '<=>')
        return sentence

    def __add_atom(self, index, sentence, connective):
        if connective == '~':
            atom = [sentence[index], sentence[index+1]]
            atom_key = "atom"+str(len(self.atomic)+1)
            self.atomic.update({atom_key:atom})
            sentence[index] = atom_key
            del sentence[index+1]
        else:
            atom = [sentence[index-1], sentence[index], sentence[index+1]]
            atom_key = "atom"+str(len(self.atomic)+1)
            self.atomic.update({atom_key:atom})
            sentence[index-1] = atom_key
            del sentence[index:index+2]
        return sentence

    def solve(self, model):
        bool_pairs = {}
        for symbol in self.symbols:
            if symbol in model:
                bool_pairs.update({symbol:model[symbol]})
            else:
                print("No boolean for all symbols.")
                sys.exit()
        for key in self.atomic:
            if len(self.atomic[key]) == 2:
                right = bool_pairs[self.atomic[key][1]]
                bool_pairs.update({key: not right})
            elif len(self.atomic[key]) == 3:
                left = bool_pairs[self.atomic[key][0]]
                right = bool_pairs[self.atomic[key][2]]
                if self.atomic[key][1] == '&':
                    bool_pairs.update({key: left and right })
                elif self.atomic[key][1] == '||':
                    bool_pairs.update({key: left or right })
                elif self.atomic[key][1] == '=>':
                    bool_pairs.update({key: not left or right})
                elif self.atomic[key][1] == '<=>':
                    bool_pairs.update({key: left == right})
            else:
                print("Atomic sentence in incorrect format: ", self.atomic[key])
                sys.exit()
        return bool_pairs[self.root[0]]
