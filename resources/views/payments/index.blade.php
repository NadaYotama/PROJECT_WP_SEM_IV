@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Payment Methods & History</h2>

    @if($payments->count() > 0)
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Payment Code</th>
                    <th>Order Number</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_code }}</td>
                    <td>{{ $payment->order->order_number ?? '#' . $payment->order->id }}</td>
                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</td>
                    <td>
                        @if($payment->status === 'success')
                            <span class="badge badge-success">Success</span>
                        @elseif($payment->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($payment->status === 'processing')
                            <span class="badge badge-info">Processing</span>
                        @else
                            <span class="badge badge-danger">Failed</span>
                        @endif
                    </td>
                    <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                    <td><a href="{{ route('customer.payments.index') }}/{{ $payment->id }}" class="btn btn-sm btn-primary">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $payments->links() }}
    @else
        <p>No payment records found.</p>
    @endif
</div>
@endsection
