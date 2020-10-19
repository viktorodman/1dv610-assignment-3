# Manual Test Cases

## Test case 1.1, Successful Todo Creation.

Successful creation of a new todo.

A success message is displayed and the new todo is added to the users todos.

## Input

* Enter all fields and select a date from the dropdown menu below the text "SET A TODO DATE".

* Click the "Submit Todo" button

## Output

* The text "Todo was created!" is displayed
* A list of all todos is displayed

---

## Test case 1.2, Failed Todo Creation.

Failed creation of a new todo.

An error message is displayed and the form for creating todos is displayed

## Input

* Leave all fields empty.

* Click the "Submit Todo" button

## Output

* A form form for creating todos is displayed
* The text "Title to short! Must be at least 1 character" is displayed at the top of the form.

--- 

## Test case 2.1, Successful Todo Deletion.

Successful Todo Deletion of a todo.

An success message is displayed and a list of the users todos is displayed.


## Input

* Select a todo from the list of todos.

* Click the "Delete Todo" button

## Output

* The text "Todo was deleted!" is displayed
* A list of all todos is displayed
