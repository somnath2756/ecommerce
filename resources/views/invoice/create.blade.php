<?php
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Invoice</h2>
    <form action="{{ route('invoices.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="customer_name" class="form-label">Customer Name</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
        </div>
        <div class="mb-3">
            <label for="invoice_date" class="form-label">Invoice Date</label>
            <input type="date" class="form-control" id="invoice_date" name="invoice_date" required>
        </div>
        <div class="mb-3">
            <label for="items" class="form-label">Items</label>
            <textarea class="form-control" id="items" name="items" rows="3" placeholder="List items and quantities" required></textarea>
        </div>
        <div class="mb-3">
            <label for="total" class="form-label">Total Amount</label>
            <input type="number" step="0.01" class="form-control" id="total" name="total" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Invoice</button>
    </form>
</div>
@endsection