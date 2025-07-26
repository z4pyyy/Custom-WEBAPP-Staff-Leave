<form action="{{ route('system.updateSystemInfo') }}" method="POST">
    @csrf
    <div class="setting-form-group">
        <label>System Name</label>
        <input type="text" name="system_name" class="form-control" value="{{ $system_info->system_name ?? '' }}" required>
    </div>
    <div class="setting-form-group">
        <label>Company Name</label>
        <input type="text" name="company_name" class="form-control" value="{{ $system_info->company_name ?? '' }}" required>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
</form>
