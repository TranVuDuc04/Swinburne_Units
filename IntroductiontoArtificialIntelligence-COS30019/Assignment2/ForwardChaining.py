class ForwardChaining:
    def __init__(self, knowledge_base):
        self.kb= knowledge_base

    def __fc_entails(self, query):
        count = {}
        deduction = {}
        agenda = []
        chain = []
        for sentence in self.kb.sentences:
            if len(sentence.conjuncts) > 0:
                count.update({sentence:len(sentence.conjuncts)})
            else:
                if sentence.head == query:
                    return True, [query] 
                agenda.append(sentence.head)

        for symbol in self.kb.symbols:
            deduction.update({symbol: False})

        while len(agenda) != 0:
            p = agenda.pop(0)
            chain.append(p)
            if not deduction[p]:
                deduction[p] = True
                for c in count:
                    if p in c.conjuncts:
                        count[c]-=1
                        if count[c] == 0:
                            if c.head == query:
                                chain.append(query)
                                return True, chain
                            agenda.append(c.head)
        return False, [] 

    def solve(self, query):
        solution_found, chain = self.__fc_entails(query)
        if solution_found:
            solution = "YES: "
            for ele in chain:
                if ele is chain[-1]:
                    solution += ele
                    
                else:
                    solution += ele + ", "
        else:
            solution = "NO"
        return solution
