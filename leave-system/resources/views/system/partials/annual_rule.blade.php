<form action="{{ route('system.updateAnnualLeave') }}" method="POST">
    @csrf
    <div class="setting-form-group">
        <label>Max Carry Forward Days</label>
        <input type="number" name="max_carry_forward_days" class="form-control" value="{{ $annual_rule->max_carry_forward_days ?? 5 }}" required>
    </div>
    <div class="setting-form-group">
        <label>Monthly Earning Rate</label>
        <input type="number" step="0.01" name="monthly_earning_rate" class="form-control" value="{{ $annual_rule->monthly_earning_rate ?? 1 }}" required>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
</form>
