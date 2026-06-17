<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Name</th>
                <th>Entries</th>
                <th>Avg</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results as $result)
                <tr>
                    <td>
                        <strong>{{ $result['name'] }}</strong>
                        <div class="small text-muted">{{ $result['students'] }} students</div>
                    </td>
                    <td>{{ $result['entries'] }}</td>
                    <td>{{ number_format($result['percentage'], 2) }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">{{ $empty }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
