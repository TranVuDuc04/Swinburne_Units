class BackwardChaining:
    def __init__(self, knowledge_base):
        self.kb = knowledge_base

    def __prove(self, removed, chain, goal):
        for sentence in self.kb.sentences:
            if len(sentence.conjuncts) == 0 and goal == sentence.head:
                chain.append(goal)
                return True, chain
        removed.append(goal)

        for sentence in self.kb.sentences:
            if goal == sentence.head:
                all_true = True
                for subgoal in sentence.conjuncts:
                    if subgoal in chain:
                        continue
                    if subgoal in removed:
                        all_true = False
                        break
                    established, chain = self.__prove(removed, chain, subgoal)
                    if not established:
                        all_true = False
                if all_true:
                    chain.append(goal)
                    return True, chain
        return False, chain

    def __bc_entails(self, goal):
        for sentence in self.kb.sentences:
            if len(sentence.conjuncts) == 0 and goal == sentence.head:
                return True, [goal]
        chain = []
        removed = []
        return self.__prove(removed, chain, goal)

    def solve(self, query):
        solution_found, chain = self.__bc_entails(query)
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
