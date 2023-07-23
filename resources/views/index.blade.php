<!-- resources/views/tasks/index.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <title>Task List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <h1>Task List</h1>
    <ul id="task-list">
        <!-- Tasks will be loaded dynamically here -->
    </ul>
    <form id="task-form">
        <input type="text" name="title" placeholder="Task title" required>
        <textarea name="description" placeholder="Task description" required></textarea>
        <button type="submit">Add Task</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // AJAX setup to include CSRF token in every AJAX request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Load tasks on page load
            loadTasks();

            // Handle form submission using AJAX
            $('#task-form').on('submit', function(e) {
                e.preventDefault();
                addTask();
            });

            // Add event delegation for the "Edit" button
            $(document).on('click', 'button.edit-task', function() {
                var taskId = $(this).data('task-id');
                var title = $(`#task-list li[data-task-id="${taskId}"] h3`).text();
                var description = $(`#task-list li[data-task-id="${taskId}"] p`).text();

                // Generate the edit form
                var editForm = `
                    <form id="edit-form-${taskId}">
                        <input type="text" name="title" value="${title}" required>
                        <textarea name="description" required>${description}</textarea>
                        <button type="button" onclick="updateTask(${taskId})">Update</button>
                        <button type="button" onclick="cancelEdit(${taskId}, '${title}', '${description}')">Cancel</button>
                    </form>
                `;

                // Replace the task details with the edit form
                $(`#task-list li[data-task-id="${taskId}"]`).html(editForm);
            });
        });

        function loadTasks() {
            $.ajax({
                url: '/tasks',
                method: 'GET',
                success: function(data) {
                    // Clear existing list items
                    $('#task-list').empty();

                    // Append tasks to the list
                    data.forEach(function(task) {
                        var taskItem = `
                            <li data-task-id="${task.id}">
                                <h3>${task.title}</h3>
                                <p>${task.description}</p>
                                <button class="edit-task" data-task-id="${task.id}">Edit</button>
                                <button onclick="deleteTask(${task.id})">Delete</button>
                            </li>
                        `;
                        $('#task-list').append(taskItem);
                    });
                }
            });
        }

        function addTask() {
            var formData = $('#task-form').serialize();

            $.ajax({
                url: '/tasks',
                method: 'POST',
                data: formData,
                success: function() {
                    loadTasks(); // Reload the task list after adding a new task

                    // Reset the form after successful submission
                    $('#task-form')[0].reset();
                }
            });
        }

        function updateTask(taskId) {
            var formData = $(`#edit-form-${taskId}`).serialize();

            $.ajax({
                url: `/tasks/${taskId}`,
                method: 'PUT',
                data: formData,
                success: function() {
                    loadTasks(); // Reload the task list after updating a task
                }
            });
        }

        function cancelEdit(taskId, title, description) {
            // Revert to the original task details when "Cancel" is clicked
            var taskDetails = `
                <h3>${title}</h3>
                <p>${description}</p>
                <button class="edit-task" data-task-id="${taskId}">Edit</button>
                <button onclick="deleteTask(${taskId})">Delete</button>
            `;
            $(`#task-list li[data-task-id="${taskId}"]`).html(taskDetails);
        }

        function deleteTask(taskId) {
            $.ajax({
                url: '/tasks/' + taskId,
                method: 'DELETE',
                success: function() {
                    loadTasks(); // Reload the task list after deleting a task
                }
            });
        }
    </script>
</body>

</html>
