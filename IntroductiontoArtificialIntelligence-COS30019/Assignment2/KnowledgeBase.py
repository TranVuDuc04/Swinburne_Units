from Sentence import Sentence
from HornForm import HornForm
import sys

class KnowledgeBase:
    def __init__(self, sentences, type):
        self.sentences = []
        self.symbols = []
        if type == 'HF' or 'GS':
            self.type = type
        else:
            print("Unknown sentence type.")
            sys.exit()
        for sentence in sentences:
            self.tell(sentence)

    def tell(self, sentence):
        if self.type == 'HF':
            new = HornForm(sentence)
        elif self.type == 'GS':
            new = Sentence(sentence)
        self.sentences.append(new)
        for symbol in new.symbols:
            if symbol not in self.symbols:
                self.symbols.append(symbol)
