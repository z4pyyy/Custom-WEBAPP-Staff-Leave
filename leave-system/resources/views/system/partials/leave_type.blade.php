<form action="{{ route('system.storeLeaveType') }}" method="POST" class="mb-3">
    @csrf
    <div class="form-inline">
        <input type="text" name="name" class="form-control mr-2" placeholder="New Leave Type" required>
        <button type="submit" class="btn btn-primary">Add</button>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Leave Type</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($leave_types as $index => $type)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $type->name }}</td>
            <td>
                <form action="{{ route('system.deleteLeaveType', $type->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
