<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Platform</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/bookings.css') }}">
</head>
<body>
<h1>Booking Platform</h1>

<h2>Create Booking</h2>
<form id="bookingForm">
    <input type="text" name="title" placeholder="Title" required>
    <input type="text" name="description" placeholder="Description">
    <input type="datetime-local" name="start_time" required>
    <input type="datetime-local" name="end_time" required>

    <select name="user_id" required>
        <option value="">Select User</option>
        @foreach(\App\Models\User::all() as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>

    <select name="client_id" required>
        <option value="">Select Client</option>
        @foreach(\App\Models\Client::all() as $client)
            <option value="{{ $client->id }}">{{ $client->name }}</option>
        @endforeach
    </select>

    <button type="submit">Create Booking</button>
    <div id="message"></div>
</form>

<h2>Bookings for Week</h2>
<input type="date" id="weekPicker" value="{{ now()->toDateString() }}">
<select id="userFilter">
    <option value="">All Users</option>
    @foreach(\App\Models\User::all() as $user)
        <option value="{{ $user->id }}">{{ $user->name }}</option>
    @endforeach
</select>
<select id="clientFilter">
    <option value="">All Clients</option>
    @foreach(\App\Models\Client::all() as $client)
        <option value="{{ $client->id }}">{{ $client->name }}</option>
    @endforeach
</select>
<button onclick="fetchBookings()">Load Bookings</button>

<table id="bookingsTable">
    <thead>
    <tr>
        <th>Title</th>
        <th>User</th>
        <th>Client</th>
        <th>Start</th>
        <th>End</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

<script src="{{ asset('js/bookings.js') }}"></script>
</body>
</html>
