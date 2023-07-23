<!DOCTYPE html>
<html>

<head>
    <title>LaraAjax</title>
    <!-- Include jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    <h1>Items List</h1>
    <ul id="item-list">
        @forelse ($items as $item)
            <li>
                <span>{{ $item->name }}</span>
                <span>{{ $item->description }}</span>
                <button class="edit-button" data-id="{{ $item->id }}">Edit</button>
                <button class="delete-button" data-id="{{ $item->id }}">Delete</button>
            </li>
        @empty
            <li>No Item Present!</li>
        @endforelse
    </ul>

    <h2>Add New Item</h2>
    <form id="item-form">
        @csrf
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <button type="submit">Add Item</button>
    </form>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Function to perform AJAX request to store a new item
        function storeItem() {
            const formData = $('#item-form').serialize();
            $.ajax({
                type: 'POST',
                url: '/items',
                data: formData,
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(error) {
                    alert('Error adding item');
                }
            });
        }

        // Function to perform AJAX request to update an existing item
        function updateItem() {
            const itemId = $('#item-form').data('id'); // Access the data-id attribute from #item-form
            const formData = $('#item-form').serialize();
            $.ajax({
                type: 'PUT',
                url: `/items/${itemId}`,
                data: formData,
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(error) {
                    alert('Error updating item');
                }
            });
        }

        // Function to perform AJAX request to delete an item
        function deleteItem(itemId) { // Accept the itemId as a parameter
            $.ajax({
                type: 'DELETE',
                url: `/items/${itemId}`,
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(error) {
                    alert('Error deleting item');
                }
            });
        }

        $(document).ready(function() {

            // Add event listeners to dynamically created buttons for edit and delete actions
            $('#item-list').on('click', '.edit-button', function(event) {
                const itemId = $(event.currentTarget).data('id'); // Use event.currentTarget
                const item = {!! $items !!}.find(item => item.id === itemId);
                $('#name').val(item.name);
                $('#description').val(item.description);
                $('#item-form').data('editing', 'true');
                $('#item-form').data('id', itemId);
            });

            // Add event listener to the #item-form for form submission
            $('#item-form').on('submit', function(event) {
                event.preventDefault();
                if ($('#item-form').data('editing')) {
                    updateItem();
                } else {
                    storeItem();
                }
            });

            // related with deleteItem
            $('#item-list').on('click', '.delete-button', function(event) {
                const itemId = $(event.currentTarget).data('id');
                deleteItem(itemId); // Call deleteItem() with the itemId parameter
            });
        });
    </script>
</body>

</html>
