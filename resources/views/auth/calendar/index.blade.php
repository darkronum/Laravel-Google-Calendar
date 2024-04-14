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

        .event-link {
            color: #007bff;
            text-decoration: underline;
            cursor: pointer;
        }

        .event-link:hover {
            color: #0056b3;
        }
    </style>
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="" target="_blank" rel="noopener noreferrer" class="btn btn-primary"></a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-g">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($events) && count($events) > 0)
                                @foreach ($events as $event)
                                    <tr>
                                        <td>
                                            <a href="{{ route('calendar.show', $event->id) }}" class="event-link">{{$event->summary}}</a>
                                        </td>
                                        <td>{{$event->start ? \Carbon\Carbon::parse($event->start->dateTime)->setTimezone('America/Sao_Paulo')->format('D d-m-Y H:i:s') : ''}}</td>
                                        <td>{{$event->end ? \Carbon\Carbon::parse($event->end->dateTime)->setTimezone('America/Sao_Paulo')->format('D d-m-Y H:i:s') : ''}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">
                                        <h3 class="text-danger text-bold text-center">No events found</h3>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<?php dd($events)?>
