from problem import Node
import sys

def iterative_deepening_search(problem):
    for depth_limit in range(problem.grid_size[0]*problem.grid_size[1]):  # Start with depth limit 0 and gradually increase
        result, visited_nodes = depth_limited_search(problem, depth_limit)
        if result is not None:  # If a solution is found, return it
            return result, visited_nodes
    return None, visited_nodes
def depth_limited_search(problem, depth_limit):
    frontier = [Node(problem.initial)]
    explored = set()
    visited_nodes = 0
    
    while frontier:
        node = frontier.pop()
        visited_nodes += 1  # Increment visited nodes when exploring a node
        
        if problem.goal_test(node.state):  
            return node, visited_nodes
        
        if node.depth < depth_limit:  # Check if the depth of the current node is within the limit
            explored.add(node.state)  # Add the current node to the explored set
            for child in node.expand(problem):
                if child.state not in explored and child not in frontier: 
                    frontier.append(child)
    
    return None, visited_nodes

"""def depth_limited_search(problem, depth_limit):
    frontier = [Node(problem.initial)]
    explored = set()
    visited_nodes = 0
    
    while frontier:
        node = frontier.pop()
        
        if problem.goal_test(node.state):  
            return node, visited_nodes
        
        if node.depth < depth_limit:  # Check if the depth of the current node is within the limit
            for child in node.expand(problem):
                if child.state not in explored and child not in frontier: 
                    explored.add(node.state)
                    frontier.append(child)
                    visited_nodes += 1             
    
    return None, visited_nodes
"""

