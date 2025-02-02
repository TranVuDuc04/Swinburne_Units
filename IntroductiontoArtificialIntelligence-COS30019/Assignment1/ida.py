from utils import PriorityQueue
from problem import Node

def ida_star_search(problem, h_score):
    cutoff = h_score(problem.initial, problem.goal)  # Initialize cutoff with the heuristic value of the initial state
    while True:
        result, new_cutoff, visited_nodes = depth_limited_search(problem, cutoff, h_score)
        if result is not None:
            return result, visited_nodes
        if new_cutoff == float('inf'):
            return None, visited_nodes  # No solution found within the search space
        cutoff = new_cutoff

def depth_limited_search(problem, cutoff, h_score):
    frontier = PriorityQueue()
    frontier.append((h_score(problem.initial, problem.goal), Node(problem.initial)))  # Initialize the frontier with the initial node
    explored = set()  
    visited_nodes = 1  
    g_cost = {problem.initial: 0} 

    while frontier.__len__ != 0:
        _, current_node = frontier.pop()  
        
        if current_node.state in explored:  
            continue
        
        if problem.goal_test(current_node.state):  
            return current_node, float('inf'), visited_nodes  # Return a solution and a new cutoff indicating no new solution can be found within the current cutoff
        
        explored.add(current_node.state)  
        
        for next_node in current_node.expand(problem):  
            next_g_cost = g_cost[current_node.state] + 1  
            f_score = next_g_cost + h_score(next_node.state, problem.goal) 
            
            if next_node.state not in explored:
                frontier.append((f_score, next_g_cost, next_node)) 
                visited_nodes += 1  
            elif frontier.__contains__(next_node):
                node = frontier.__getitem__(next_node)
                if f_score < node.cost:
                    frontier.__delitem__(next_node)
                    frontier.append((f_score, next_g_cost, node))
    
    return None, cutoff, visited_nodes  

