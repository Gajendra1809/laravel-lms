<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="charts-container" style="margin-left: 50px; margin-right: 50px">
        <h2>Library Analytics</h2>

        <div style="display: flex; justify-content: space-between">
            <div>
                <h3>Most Borrowed Books</h3>
                @include('charts.most-borrowed-books')
            </div>
    
            <div>
                <h3>Availability Status</h3>
                @include('charts.availability-status')
            </div>
        </div>

        <br><br><br><br><br>

        <div style="display: flex; justify-content: space-between">
            <div>
                <h3>Weekly Active Borrowers</h3>
                @include('charts.weekly-active-borrowers')
            </div>

            <div>
                <h3>Longest Borrowed Books</h3>
                @include('charts.longest-borrowed-books')
            </div>
        </div>

    </div>
@endsection
