from Sentence import Sentence

class TruthTable:
    def __init__(self, knowledge_base):
        self.kb = knowledge_base
        self.count = 0

    def __tt_entails(self, alpha):
        symbols = self.kb.symbols
        for symbol in alpha.symbols:
            if symbol not in symbols:
                symbols.append(symbol)
        return self.__tt_check_all(alpha, symbols, {})

    def __tt_check_all(self, alpha, symbols, model):
        if len(symbols) == 0:
            all_true = True
            for sentence in self.kb.sentences:
                if not sentence.solve(model):
                    all_true = False
            if all_true:
                alpha_solution = alpha.solve(model)
                if alpha_solution:
                    self.count += 1
                return alpha_solution
            else:
                return True
        else:
            p = symbols[0]
            rest = symbols[1:]
            model1 = model.copy()
            model1.update({p: True})
            model2 = model.copy()
            model2.update({p: False})
            return (self.__tt_check_all(alpha, rest, model1) and
                    self.__tt_check_all(alpha, rest, model2))

    def solve(self, ask):
        alpha = Sentence(ask)
        solution_found = self.__tt_entails(alpha)
        if solution_found and (self.count > 0):
            solution = "YES: " + str(self.count)
        else:
            solution = "NO"
        return solution
