<!DOCTYPE html>
<html>
<head><title>Create New Form</title></head>
<body>
    <h1>Create New Form</h1>
    <form method="POST" action="{{ route('forms.store') }}">
        @csrf
        <label>Name:</label><input type="text" name="name" required><br>
        <label>Address:</label><input type="text" name="address" required><br>
        <label>Contact Number:</label><input type="number" name="contact_number" required><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
