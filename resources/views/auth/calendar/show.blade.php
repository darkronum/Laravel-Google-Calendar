@section('css')
    <style>
          body {
                font-family: Arial, sans-serif;
                background-color: #f4f7f6;
                margin: 0;
                padding: 20px;
            }
            .table-container {
                overflow-x: auto;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 25px 0;
                font-size: 0.9em;
                min-width: 400px;
                border-radius: 5px 5px 0 0;
                overflow: hidden;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            }
            thead tr {
                background-color: #009879;
                color: #ffffff;
                text-align: left;
            }
            th, td {
                padding: 12px 15px;
            }
            tbody tr {
                border-bottom: 1px solid #dddddd;
            }
            tbody tr:nth-of-type(even) {
                background-color: #f3f3f3;
            }
            tbody tr:last-of-type {
                border-bottom: 2px solid #009879;
            }
            tbody tr.active-row {
                font-weight: bold;
                color: #009879;
            }
    </style>
@endsection
<x-app-layout>
 <x-slot name="header">
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
    <a href="" target="_blank" rel="noopener noreferrer" class="btn btn-primary"></a>
  </h2>
 </xslot>
 <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-g">
        <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Event Details') }}</div>

                    <div class="card-body">
                        <h5>Title: {{ $event->summary }}</h5>
                        <p>Description: {{ $event->description }}</p>
                        <p>Start Time: {{ $event->start->dateTime }}</p>
                        <p>End Time: {{ $event->end->dateTime }}</p>

                        <form method="POST" action="{{ route('calendar.destroy', $event->id) }}" class="mb-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Excluir Evento</button>
        </form>



                        <form method="POST" action="{{ route('calendar.update', $event->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>

                                <div class="col-md-6">
                                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $event->summary }}" required autofocus>

                                    @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                                <div class="col-md-6">
                                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" required>{{ $event->description }}</textarea>

                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
    <label for="start" class="col-md-4 col-form-label text-md-right">{{ __('Start Time') }}</label>

    <div class="col-md-6">
        <input id="start" type="datetime-local" class="form-control @error('start') is-invalid @enderror" name="start" value="{{ date('Y-m-d\TH:i', strtotime($event->start->dateTime . ' -3 hours')) }}" required autofocus>

        @error('start')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label for="end" class="col-md-4 col-form-label text-md-right">{{ __('End Time') }}</label>

    <div class="col-md-6">
        <input id="end" type="datetime-local" class="form-control @error('end') is-invalid @enderror" name="end" value="{{ date('Y-m-d\TH:i', strtotime($event->end->dateTime . ' -3 hours')) }}" required autofocus>

        @error('end')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>


                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Update Event') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
 </div>
 <!-- < ?php dd($events) ?> -->
</x-app-layout>
