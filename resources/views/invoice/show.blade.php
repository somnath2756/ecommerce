<?php
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Invoice Details</h2>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Invoice #{{ $invoice->id }}</h5>
            <p><strong>Customer Name:</strong> {{ $invoice->customer_name }}</p>
            <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
            <p><strong>Items:</strong></p>
            <pre>{{ $invoice->items }}</pre>
            <p><strong>Total Amount:</strong> ${{ number_format($invoice->total, 2) }}</p>
        </div>
    </div>
    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back to List</a>
    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary">Edit Invoice</a>
    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this invoice?')">Delete Invoice</button>
    </form>
</div>
@endsection
