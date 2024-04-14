@section('css')
    <style>
form {
    max-width: 500px;
    margin: 0 auto;
    padding: 1em;
    background: #fff;
    border-radius: 5px;
}

div {
    margin-bottom: 1em;
}

label {
    display: block;
    margin-bottom: .5em;
}

input[type="text"],
input[type="email"],
textarea {
    width: 100%;
    padding: .5em;
    border: 1px solid #ccc;
    border-radius: 3px;
}

button[type="submit"] {
    padding: 10px 15px;
    background: #007bff;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
}

button[type="submit"]:hover {
    background: #0056b3;
}
    </style>
@endsection
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ url()->previous() }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Voltar</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-g">
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form method="POST" action="{{route('calendar.store')}}">
                    @csrf
                    <div>
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Event title here">
                    </div>
                    <div>
                        <label for="start">Start Date:</label>
                        <input type="date" id="start" name="start" value="{{ old('start') }}" required >
                        <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" required >
                    </div>
                    <div>
                        <label for="end">End Date:</label>
                        <input type="date" id="end" name="end" value="{{ old('end') }}" required>
                        <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                    </div>
                    <div>
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" placeholder="Your description here..." required>{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <button type="submit">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


