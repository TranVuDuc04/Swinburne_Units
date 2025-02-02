from utils import *
from problem import Node

def greedy_best_first_search(problem, h_score):
    frontier = PriorityQueue()
    frontier.append((h_score(problem.initial, problem.goal), Node(problem.initial)))  # Initialize the frontier with the initial node
    explored = set()  
    explored.add(Node(problem.initial).state)
    visited_nodes = 1  
    
    while frontier.__len__() != 0:  
        _, current_node = frontier.pop() 
        
        if problem.goal_test(current_node.state):  
            return current_node, visited_nodes
        
        for next_node in current_node.expand(problem):   
            f_score = h_score(next_node.state, problem.goal)  
            
            if next_node.state not in explored and not frontier.__contains__(next_node):  
                frontier.append((f_score, next_node))   
                explored.add(next_node.state) 
                visited_nodes += 1  
    return None, visited_nodes    

def h_score(state, goal_states):
    # Calculate the Manhattan distance from the state to the nearest goal state
    distances = [abs(state[0] - goal_state[0]) + abs(state[1] - goal_state[1]) for goal_state in goal_states]
    min_distance = min(distances)
    return min_distance
