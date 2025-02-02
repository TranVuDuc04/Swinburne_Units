from problem import *
from dfs import *
from bfs import *
from gbfs import *
from astar import *
from iddfs import *
from ida import *
import sys
#class
myfile = open("RobotNav-test.txt", "r")
goal_states = []
walls = []
node_map = {}
grid_size = tuple(map(int, myfile.readline().strip().strip("[]").split(",")))
initial_state = tuple(map(int, myfile.readline().strip()[1:-1].split(',')))

line = myfile.readline().strip()
goal_strings = line.split('|')
for goal_str in goal_strings:
    goal_values = tuple(map(int, goal_str.strip()[1:-1].split(',')))
    goal_states.append(goal_values)

while True:
  line  = myfile.readline().strip()
  if line == "":
    break
  block_values = tuple(map(int, line.strip()[1:-1].split(',')))
  walls.append(block_values)

problem = GridProblem(grid_size, initial_state, goal_states, walls)


def main():
    if len(sys.argv) != 2:
        return
    argument = sys.argv[1]
    
    #the arguments
    if argument == "bfs":
        solution_node , visited_nodes = breadth_first_search(problem) 
        if solution_node:
            print(solution_node, visited_nodes)
            solution_directions = solution_node.solution()
            direction_names = [direction for _, direction in solution_directions]
            print(direction_names)
        else:
            print("No goal is reachable;", visited_nodes)

    if argument == "dfs":
        solution_node , visited_nodes = depth_first_search(problem)
        if solution_node:
            print(solution_node, visited_nodes)
            solution_directions = solution_node.solution()
            direction_names = [direction for _, direction in solution_directions]
            print(direction_names)
        else:
            print("No goal is reachable;", visited_nodes)

    if argument == "gbfs":
        solution_node , visited_nodes = greedy_best_first_search(problem, h_score)
        if solution_node:
            print(solution_node, visited_nodes)
            solution_directions = solution_node.solution()
            direction_names = [direction for _, direction in solution_directions]
            print(direction_names)
        else:
            print("No goal is reachable;", visited_nodes)

    if argument == "astar":
        solution_node , visited_nodes = a_star_search(problem, h_score)
        if solution_node:
            print(solution_node, visited_nodes)
            solution_directions = solution_node.solution()
            direction_names = [direction for _, direction in solution_directions]
            print(direction_names)
        else:
            print("No goal is reachable;", visited_nodes)

    if argument == "iddfs":
        solution_node , visited_nodes = iterative_deepening_search(problem)
        if solution_node:
            print(solution_node, visited_nodes)
            solution_directions = solution_node.solution()
            direction_names = [direction for _, direction in solution_directions]
            print(direction_names)
        else:
            print("No goal is reachable;", visited_nodes)

    if argument == "ida":
        solution_node , visited_nodes = ida_star_search(problem, h_score)
        if solution_node:
            print(solution_node, visited_nodes)
            solution_directions = solution_node.solution()
            direction_names = [direction for _, direction in solution_directions]
            print(direction_names)
        else:
            print("No goal is reachable;", visited_nodes)

if __name__ == "__main__":
    main()

