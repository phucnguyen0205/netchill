@extends('layouts.app')
@section('content')
<div class="container" style="max-width:720px;">
  <h3>Nạp tiền</h3>
  <form method="POST" action="{{ route('wallet.topup.create') }}">
    @csrf
    <div class="mb-3">
      <label>Số tiền (VND)</label>
      <input type="number" name="amount" class="form-control" min="1000" value="10000" required>
    </div>
    <button class="btn btn-warning">Chuyển tới VNPAY (Sandbox)</button>
  </form>
</div>
@endsection
