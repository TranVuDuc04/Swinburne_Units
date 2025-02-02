from tkinter import *
from tkinter import ttk
from problem import *
from dfs import *
from gbfs import *
from iddfs import *
from astar import *
from problem import Node
from collections import deque
import time
import tkinter as tk
import sys
#class
myfile = open("RobotNav-test.txt", "r")
goal_states = []
walls = []
node_map = {}
is_resetting = False
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



def draw_maze(canvas, problem):
    grid_size = problem.grid_size
    cell_size = 70  # Adjust this value as needed for the size of each cell
    canvas.create_rectangle(0, 0, grid_size[1] * cell_size, grid_size[0] * cell_size , fill='white', outline='')
    # Draw grid lines and cells
    for y in range(grid_size[1]):
        for x in range(grid_size[0]):
            cell_x = x * cell_size
            cell_y = y * cell_size
            canvas.create_rectangle(cell_x, cell_y, cell_x + cell_size, cell_y + cell_size, outline='gray')

    for y in range(grid_size[0] + 1):
        canvas.create_line(0, y * cell_size, WIDTH, y * cell_size, fill="gray")

# Draw vertical lines for the entire maze
    for x in range(grid_size[1] + 1):
        canvas.create_line(x * cell_size, 0, x * cell_size, HEIGHT, fill="gray")


    # Draw walls
    for block_values in problem.walls:
        x, y, w, h = block_values
        canvas.create_rectangle(x * cell_size, y * cell_size,
                                (x + w) * cell_size, (y + h) * cell_size,
                                fill='black', outline='')

    # Draw start position
    start_x, start_y = problem.initial
    canvas.create_rectangle(start_x * cell_size, start_y * cell_size,
                       (start_x + 1) * cell_size, (start_y + 1) * cell_size,
                       fill='red', outline='')

    # Draw goal positions
    for goal_state in problem.goal:
        goal_x, goal_y = goal_state
        canvas.create_rectangle(goal_x * cell_size, goal_y * cell_size,
                           (goal_x + 1) * cell_size, (goal_y + 1) * cell_size,
                           fill='LightSkyBlue1', outline='')

def reset_maze(canvas, problem):
    global is_resetting #terminate all the ongoing process
    is_resetting = True
    grid_size = problem.grid_size
    cell_size = 70  # Adjust this value as needed for the size of each cell
    canvas.create_rectangle(0, 0, grid_size[1] * cell_size, grid_size[0] * cell_size , fill='white', outline='')
    # Draw grid lines and cells
    for y in range(grid_size[1]):
        for x in range(grid_size[0]):
            cell_x = x * cell_size
            cell_y = y * cell_size
            canvas.create_rectangle(cell_x, cell_y, cell_x + cell_size, cell_y + cell_size, outline='gray')

    for y in range(grid_size[0] + 1):
        canvas.create_line(0, y * cell_size, WIDTH, y * cell_size, fill="gray")

# Draw vertical lines for the entire maze
    for x in range(grid_size[1] + 1):
        canvas.create_line(x * cell_size, 0, x * cell_size, HEIGHT, fill="gray")


    # Draw walls
    for block_values in problem.walls:
        x, y, w, h = block_values
        canvas.create_rectangle(x * cell_size, y * cell_size,
                                (x + w) * cell_size, (y + h) * cell_size,
                                fill='black', outline='')

    # Draw start position
    start_x, start_y = problem.initial
    canvas.create_rectangle(start_x * cell_size, start_y * cell_size,
                       (start_x + 1) * cell_size, (start_y + 1) * cell_size,
                       fill='red', outline='')

    # Draw goal positions
    for goal_state in problem.goal:
        goal_x, goal_y = goal_state
        canvas.create_rectangle(goal_x * cell_size, goal_y * cell_size,
                           (goal_x + 1) * cell_size, (goal_y + 1) * cell_size,
                           fill='LightSkyBlue1', outline='')
        
    '''canvas_frontier.delete("all") ''' 
    canvas_solution.delete("all") 
 

def run_algorithm():
    global is_resetting
    is_resetting = False
    # choose algorithm
    if algMenu.get() == 'A star':
        solution_node, visited_nodes = a_star_search(problem, h_score)
        if solution_node:
            visited_nodes_text = "Created nodes: " + str(visited_nodes)
            canvas_solution.create_text(50, 15, anchor="nw", text=visited_nodes_text, font=font, fill="black")
            canvas_solution.create_text(50, 50, anchor="nw", text="Solution node:", font=font, fill="black")
            
            solution_path = ", ".join(map(str, solution_node))
            lines = [solution_path[i:i+40] for i in range(0, len(solution_path), 40)]
            for i, line in enumerate(lines):
                canvas_solution.create_text(50, 70 + i*20, anchor="nw", text=line, font=font, fill="black")
        else:
            canvas_solution.create_text(100, 15, text="No Solution found", font=font, fill="black")

    elif algMenu.get() == 'Breadth First Search':
        solution_node, visited_nodes = breadth_first_search(problem)
        print(solution_node)
        if solution_node:
            visited_nodes_text = "Created nodes: " + str(visited_nodes)
            canvas_solution.create_text(50, 15, anchor="nw", text=visited_nodes_text, font=font, fill="black")
            canvas_solution.create_text(50, 50, anchor="nw", text="Solution node:", font=font, fill="black")
            solution_path = ", ".join(map(str, solution_node))
            lines = [solution_path[i:i+40] for i in range(0, len(solution_path), 40)]
            for i, line in enumerate(lines):
                canvas_solution.create_text(50, 70 + i*20, anchor="nw", text=line, font=font, fill="black")
            
            # Draw the solution path after finding the solution node
            draw_path(solution_node.state)
        else:
            canvas_solution.create_text(100, 15, text="No Solution found", font=font, fill="black")


    elif algMenu.get() == 'Depth First Search':
        solution_node , visited_nodes = depth_first_search(problem) 
        if solution_node:
            visited_nodes_text = "Created nodes: " + str(visited_nodes)
            canvas_solution.create_text(50, 15, anchor="nw", text=visited_nodes_text, font=font, fill="black")
            canvas_solution.create_text(50, 50, anchor="nw", text="Solution node:", font=font, fill="black")
            
            solution_path = ", ".join(map(str, solution_node))
            lines = [solution_path[i:i+40] for i in range(0, len(solution_path), 40)]
            for i, line in enumerate(lines):
                canvas_solution.create_text(50, 70 + i*20, anchor="nw", text=line, font=font, fill="black")
        else:
            canvas_solution.create_text(100, 15, text="No Solution found", font=font, fill="black")

    elif algMenu.get() == 'Greedy Best First Search':
        solution_node, visited_nodes = greedy_best_first_search(problem, h_score) 
        if solution_node:
            visited_nodes_text = "Created nodes: " + str(visited_nodes)
            canvas_solution.create_text(50, 15, anchor="nw", text=visited_nodes_text, font=font, fill="black")
            canvas_solution.create_text(50, 50, anchor="nw", text="Solution node:", font=font, fill="black")
            
            solution_path = ", ".join(map(str, solution_node))
            lines = [solution_path[i:i+40] for i in range(0, len(solution_path), 40)]
            for i, line in enumerate(lines):
                canvas_solution.create_text(50, 70 + i*20, anchor="nw", text=line, font=font, fill="black")
        else:
            canvas_solution.create_text(100, 15, text="No Solution found", font=font, fill="black")

    elif algMenu.get() == 'Iterative Deepening Search':
        solution_node, visited_nodes = iterative_deepening_search(problem)
        if solution_node:
            visited_nodes_text = "Created nodes: " + str(visited_nodes)
            canvas_solution.create_text(50, 15, anchor="nw", text=visited_nodes_text, font=font, fill="black")
            canvas_solution.create_text(50, 50, anchor="nw", text="Solution node:", font=font, fill="black")
            
            solution_path = ", ".join(map(str, solution_node))
            lines = [solution_path[i:i+40] for i in range(0, len(solution_path), 40)]
            for i, line in enumerate(lines):
                canvas_solution.create_text(50, 70 + i*20, anchor="nw", text=line, font=font, fill="black")
        else:
            canvas_solution.create_text(100, 15, text="No Solution found", font=font, fill="black")

def breadth_first_search(problem):
    node = Node(problem.initial)
    if problem.goal_test(node.state):
        return node, 1
    paths = {problem.initial: [problem.initial]}  # Include the initial node in the path
    
    # Draw the initial node
    x, y = problem.initial
    draw_cell(x, y, 'DodgerBlue4')
    time.sleep(0.1)  
    UI_frame.update() 

    frontier = deque([node])
    explored = set()
    visited_nodes = 1  

    while frontier:
        if is_resetting:
            return None, 0
        node = frontier.popleft()
        explored.add(node.state)
        if problem.goal_test(node.state):
            draw_path(paths[node.state]) 
            return paths[node.state], visited_nodes
        for child in node.expand(problem):
            if child.state not in explored and child not in frontier:
                visited_nodes += 1
                
                frontier.append(child)
                x, y = child.state
                draw_cell(x, y, 'DodgerBlue4')  # Draw the current node as visited
                time.sleep(0.1)  
                UI_frame.update() 
                paths[child.state] = paths[node.state] + [child.state]

    return None, visited_nodes  

def depth_first_search(problem):
    frontier = [Node(problem.initial)]
    explored = set()
    visited_nodes = 1
    paths = {problem.initial: [problem.initial]}  # Include the initial node in the path
    
    # Draw the initial node
    x, y = problem.initial
    draw_cell(x, y, 'DodgerBlue4')
    time.sleep(0.1)  
    UI_frame.update() 

    while frontier:
        if is_resetting:
            return None, 0
        node = frontier.pop()
        if problem.goal_test(node.state):
            return paths[node.state], visited_nodes
        
        explored.add(node.state)
        for child in node.expand(problem):
            if child.state not in explored and child not in frontier:
                visited_nodes += 1
                frontier.append(child)
                x, y = child.state
                draw_cell(x, y, 'DodgerBlue4')  # Draw the current node as visited
                time.sleep(0.1)  
                UI_frame.update() 
                paths[child.state] = paths[node.state] + [child.state]  
                if problem.goal_test(child.state):
                    paths[child.state] = paths[node.state] + [child.state]  # Update paths for the goal child
                    draw_path(paths[child.state])  
                    return paths[child.state], visited_nodes
    return None, visited_nodes

def greedy_best_first_search(problem, h_score):
    frontier = PriorityQueue()
    frontier.append((h_score(problem.initial, problem.goal), Node(problem.initial)))  # Initialize the frontier with the initial node
    explored = set()  
    explored.add(Node(problem.initial).state)
    visited_nodes = 1  
    paths = {problem.initial: [problem.initial]}  # Include the initial node in the path
    
    # Draw the initial node
    x, y = problem.initial
    draw_cell(x, y, 'DodgerBlue4')
    time.sleep(0.1)  
    UI_frame.update() 

    while frontier.__len__() != 0:  
        if is_resetting:
            return None, 0
        _, current_node = frontier.pop() 
        
        if problem.goal_test(current_node.state):  
            draw_path(paths[current_node.state])
            return paths[current_node.state], visited_nodes
        
        for next_node in current_node.expand(problem):   
            f_score = h_score(next_node.state, problem.goal)  
            
            if next_node.state not in explored and not frontier.__contains__(next_node):  
                frontier.append((f_score, next_node))   
                explored.add(next_node.state) 
                x, y = next_node.state
                draw_cell(x, y, 'DodgerBlue4')  # Draw the current node as visited
                time.sleep(0.1)  
                UI_frame.update() 
                paths[next_node.state] = paths[current_node.state] + [next_node.state] 
                visited_nodes += 1  
    return None, visited_nodes    

def a_star_search(problem, h_score):
    frontier = PriorityQueue()
    frontier.append((0, Node(problem.initial)))  # Initialize the frontier with the initial node
    g_cost = {problem.initial: 0}  # Dictionary to store the g-cost for each node
    explored = set()  
    explored.add(Node(problem.initial).state)
    visited_nodes = 1  
    paths = {problem.initial: [problem.initial]}  

    x, y = problem.initial
    draw_cell(x, y, 'DodgerBlue4')
    time.sleep(0.05)  
    UI_frame.update() 
    
    while frontier.__len__() != 0:  
        if is_resetting:
            return None, 0
        _, current_node = frontier.pop()  

        if problem.goal_test(current_node.state):
            draw_path(paths[current_node.state])  # Draw the final path
            return paths[current_node.state], visited_nodes 

        for next_node in current_node.expand(problem):  
            next_g_cost = g_cost[current_node.state] + 1  
            f_score = next_g_cost + h_score(next_node.state, problem.goal)  
            
            if next_node.state not in explored:  
                frontier.append((f_score, next_node)) 
                g_cost[next_node.state] = next_g_cost  
                explored.add(next_node.state)
                visited_nodes += 1 
                paths[next_node.state] = paths[current_node.state] + [next_node.state]  # Update the path 
                x, y = next_node.state
                draw_cell(x, y, 'DodgerBlue4')  # Draw the current node as visited
                time.sleep(0.05)  
                UI_frame.update() 

            elif frontier.__contains__(next_node):
                node = frontier.__getitem__(next_node)
                paths[next_node.state] = paths[current_node.state] + [next_node.state]  # Update the path
                if f_score < node[0]:
                    frontier.__delitem__(next_node)
                    frontier.append((f_score, next_g_cost, node))
    
    return None, visited_nodes  

def iterative_deepening_search(problem):
    for depth_limit in range(sys.maxsize):  
        result, visited_nodes = depth_limited_search(problem, depth_limit)
        if result is not None:  
            return result, visited_nodes

def depth_limited_search(problem, depth_limit):
    frontier = [Node(problem.initial)]
    explored = set()
    explored.add(Node(problem.initial).state)
    frontier_states = set()  # Keep track of frontier states for efficient checking
    visited_nodes = 1
    paths = {problem.initial: [problem.initial]}  

    x, y = problem.initial
    draw_cell(x, y, 'DodgerBlue4')
    time.sleep(0.005)  
    UI_frame.update() 
    
    while frontier:
        node = frontier.pop()
        
        if problem.goal_test(node.state): 
            for state in explored:
                x, y = state
                draw_cell(x, y, 'DodgerBlue4')  
                time.sleep(0.005)  
                UI_frame.update()
            draw_path(paths[node.state])
            return paths[node.state], visited_nodes
        
        if node.depth < depth_limit:  
    
            for child in node.expand(problem):
                if child.state not in explored and child.state not in frontier_states:
                    frontier.append(child)  
                    frontier_states.add(child.state)
                    explored.add(child.state)
                    visited_nodes += 1
                    paths[child.state] = paths[node.state] + [child.state]  
    return None, visited_nodes



def h_score(state, goal_states):
    distances = [abs(state[0] - goal_state[0]) + abs(state[1] - goal_state[1]) for goal_state in goal_states]
    min_distance = min(distances)
    return min_distance

def draw_cell(x, y, color):
    cell_size = 70  
    canvas.create_rectangle(x * cell_size, y * cell_size,
                            (x + 1) * cell_size, (y + 1) * cell_size,
                            fill=color, outline='black')

def draw_selected_cell(x, y, color):
    cell_size = 70  # Adjust this value as needed for the size of each cell
    padding = 20 # Adjust the padding value as needed
    canvas.create_rectangle(x * cell_size + padding, y * cell_size + padding,
                            (x + 1) * cell_size - padding, (y + 1) * cell_size - padding,
                            fill=color, outline='')

def draw_path(path):
    if not path:
        return
    for x, y in path:
        draw_selected_cell(x, y, 'steelblue2')  # Draw each cell in the path
 

# Initialize main window
root = Tk()
root.title('Map Creation by Vu Duc Tran test')
root.maxsize(4000,4000)
root.config(bg='MediumPurple1')
font = ("Times New Roman", 20)

selected_alg = StringVar()
selected_bld = StringVar()
WIDTH = 70 * grid_size[1]
HEIGHT = 70 * grid_size[0]
PAD = 10
grid = []

# user interface
UI_frame = Frame(root, height=200, bg='LightSkyBlue1', highlightthickness=2, highlightbackground='black')
UI_frame.grid(row=0, column=0, padx= 50, pady=PAD)
    
# create canvas
canvas = Canvas(root, width=WIDTH, height=HEIGHT, bg='white')
canvas.grid(row=0, column=1, padx=10, pady=PAD) 


canvas_solution = Canvas(root, width=35*11, height=35*5, bg='white')
canvas_solution.grid(row=1, column=1, padx=10, pady=PAD) 


# Call your a_star_search function passing the required arguments

# Draw the maze on the canvas
draw_maze(canvas, problem)


# create function
algMenu = ttk.Combobox(UI_frame, textvariable=selected_alg, 
                       values=['A star', 'Breadth First Search', 'Depth First Search', 'Greedy Best First Search', 'Iterative Deepening Search'], font = font)
algMenu.grid(row=3, column=0, padx= PAD, pady=(20, 5), sticky=W)
algMenu.current(0)

Button(UI_frame, text="Start Search", command= run_algorithm, font = ("Times New Roman", 14),
        highlightbackground=UI_frame.cget('background')).grid(row=5, column=0, padx=5, pady=(10, 10))

Button(UI_frame, text='Reset',command=lambda: reset_maze(canvas, problem), font = ("Times New Roman", 14),
      highlightbackground=UI_frame.cget('background')).grid(row=6, column=0, padx=5, pady=(20, 30))

# run loop 
root.mainloop()

