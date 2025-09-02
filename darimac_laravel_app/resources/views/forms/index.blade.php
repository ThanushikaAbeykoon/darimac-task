<!DOCTYPE html>
<html>
<head><title>Your Forms</title></head>
<body>
    <h1>Your Forms</h1>
    @if ($forms->isEmpty())
        <p>No forms submitted yet. <a href="{{ route('forms.create') }}">Add a new form</a>.</p>
    @else
        <ul>
            @foreach ($forms as $form)
                <li>{{ $form->name }} - <a href="{{ route('forms.download', $form->id) }}">Download PDF</a></li>
            @endforeach
        </ul>
    @endif
    <a href="{{ route('forms.create') }}">Add New Form</a>
</body>
</html>
