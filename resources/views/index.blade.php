<div class="container">
    <h1>Grouped Data by Date</h1>

    @foreach($groupedData as $date => $rows)
        <h2>{{ $date }}</h2>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->date->format('d.m.Y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach
</div>
