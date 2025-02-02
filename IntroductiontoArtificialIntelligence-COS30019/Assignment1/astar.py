from utils import *
from problem import Node

def a_star_search(problem, h_score):
    frontier = PriorityQueue()
    frontier.append((0, Node(problem.initial)))  # Initialize the frontier with the initial node
    g_cost = {problem.initial: 0}  # Dictionary to store the g-cost for each node
    explored = set()  
    visited_nodes = 1  
    
    while frontier.__len__ != 0:  
        _, current_node = frontier.pop()  
        
        if current_node.state in explored:  
            continue
        
        if problem.goal_test(current_node.state):  
            return current_node, visited_nodes
        
        explored.add(current_node.state)  
        
        for next_node in current_node.expand(problem):  
            next_g_cost = g_cost[current_node.state] + 1  
            f_score = next_g_cost + h_score(next_node.state, problem.goal)  
            
            if next_node.state not in explored:  
                frontier.append((f_score, next_node))  
                g_cost[next_node.state] = next_g_cost  
                visited_nodes += 1  

            elif frontier.__contains__(next_node):
                node = frontier.__getitem__(next_node)
                if f_score < node[0]:
                    frontier.__delitem__(next_node)
                    frontier.append((f_score, next_g_cost, node))
    
    return None, visited_nodes  

def h_score(state, goal_states):
    # Calculate the Manhattan distance from the state to the nearest goal state
    distances = [abs(state[0] - goal_state[0]) + abs(state[1] - goal_state[1]) for goal_state in goal_states]
    min_distance = min(distances)
    return min_distance