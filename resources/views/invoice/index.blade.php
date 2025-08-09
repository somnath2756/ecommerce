<?php
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Invoices</h2>
    <a href="{{ route('invoices.create') }}" class="btn btn-success mb-3">Create New Invoice</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Invoice Date</th>
                <th>Total Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->customer_name }}</td>
                    <td>{{ $invoice->invoice_date }}</td>
                    <td>${{ number_format($invoice->total, 2) }}</td>
                    <td>
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No invoices found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div>
        {{ $invoices->links() }}
    </div>