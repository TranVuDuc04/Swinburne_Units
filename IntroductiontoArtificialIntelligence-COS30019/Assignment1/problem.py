from utils import is_in
import math

class Problem:

  def __init__(self, initial, goal=None):
    self.initial = initial
    self.goal = goal

  def actions(self, state):
    raise NotImplementedError

  def result(self, state, action):
    raise NotImplementedError

  def goal_test(self, state):
    if isinstance(self.goal, list):
      return is_in(state, self.goal)
    else:
      return state == self.goal

  def path_cost(self, c, state1, action, state2):
    return c + 1

  def value(self, state):
    raise NotImplementedError

class Node:

  def __init__(self, state, parent=None, action=None, path_cost=0):
    self.state = state
    self.parent = parent
    self.action = action
    self.path_cost = path_cost
    self.depth = 0
    if parent:
      self.depth = parent.depth + 1

  def __repr__(self):
    return "<Node {}>".format(self.state)

  def __lt__(self, node):
    return self.state < node.state

  def expand(self, problem):
    return [self.child_node(problem, action)
      for action in problem.actions(self.state)]

  def child_node(self, problem, action):
    next_state = problem.result(self.state, action)
    next_node = Node(next_state, self, action, problem.path_cost(self.path_cost, self.state, action, next_state))
    return next_node

  def solution(self):
    return [node.action for node in self.path()[1:]]

  def path(self):
    node, path_back = self, []
    while node:
      path_back.append(node)
      node = node.parent
    return list(reversed(path_back))

  def __eq__(self, other):
    return isinstance(other, Node) and self.state == other.state

  def __hash__(self):
    return hash(self.state)
  

class GridProblem(Problem):
    def __init__(self, grid_size, initial_state, goal_states, walls):
        super().__init__(initial_state, goal_states)
        self.grid_size = grid_size
        self.walls = walls

    def actions(self, state):
        x, y = state
        possible_actions = [
            ((x, y-1), 'up'),
            ((x-1, y), 'left'),
            ((x, y+1), 'down'),
            ((x+1, y), 'right')
        ]
        valid_actions = []
        '''for action in possible_actions:
            if self.is_valid(action[0]):
                valid_actions.append(action)
        return valid_actions'''
        for action, direction in possible_actions:
                if self.is_valid(action):
                    valid_actions.append((action, direction))
        return valid_actions

    def result(self, state, action):
        return action[0]

    def goal_test(self, state):
        return state in self.goal

    def is_valid(self, action):
        x, y = action
        if x < 0 or x >= self.grid_size[1] or y < 0 or y >= self.grid_size[0]:
            return False  # Out of bounds
        for wall in self.walls:
            wx, wy, w_width, w_height = wall
            if wx <= x < wx + w_width and wy <= y < wy + w_height:
                return False  # Collides with a wall
        return True

    