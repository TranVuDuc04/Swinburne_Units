from problem import Node
from collections import deque

def breadth_first_search(problem):
    node = Node(problem.initial)
    if problem.goal_test(node.state):
        return node, 1

    frontier = deque([node])
    explored = set()
    visited_nodes = 1  

    while frontier:
        node = frontier.popleft()
        explored.add(node.state)
        if problem.goal_test(node.state):
                    return node, visited_nodes
        for child in node.expand(problem):
            if child.state not in explored and child not in frontier:
                visited_nodes += 1
                
                frontier.append(child)

    return None, visited_nodes  


