from problem import Node

def depth_first_search(problem):
    frontier = [Node(problem.initial)]
    explored = set()
    visited_nodes = 1 
    
    while frontier:
        node = frontier.pop()

        if problem.goal_test(node.state):
            return node, visited_nodes
        
        explored.add(node.state)
        for child in node.expand(problem):
            if child.state not in explored and child not in frontier:
                visited_nodes += 1
                frontier.append(child)

    return None, visited_nodes
