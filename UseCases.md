# Use-Cases Todo App

## UC1 Create new Todo

### Preconditions

User is authenticated and has clicked on the "Create New Todo" navigation item.

### Main scenario

1. Starts when user wants to create a new todo.

2. The system presents a form for creating a new todo.

3. The user enters a title, description and a date and clicks on the submit button.

4. The system presents a list of all todos and presents a confirmation message.


### Alternate Scenarios

4b The user did not enter all fields.
    1. The system presents an error message
    2. Step 2 in main scenario

## UC2 Delete a Todo

### Preconditions


The user is authenticated.

The user has created atleast one todo.

The user has selected a todo from the list of todos.

### Main scenario

1. Starts when user wants to delete a todo.

2. The system presents a delete button.

3. The user clicks the delete button

4. The system presents a list of all todos and presents a confirmation message.


