@extends('layouts.dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Settings</h1>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">General Settings</h2>
            <form>
                <div class="mb-4">
                    <label for="site_name" class="block text-gray-700 text-sm font-bold mb-2">Site Name:</label>
                    <input type="text" id="site_name" name="site_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="My Awesome Dashboard">
                </div>
                <div class="mb-4">
                    <label for="admin_email" class="block text-gray-700 text-sm font-bold mb-2">Admin Email:</label>
                    <input type="email" id="admin_email" name="admin_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="admin@example.com">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save General Settings</button>
            </form>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Security Settings</h2>
            <form>
                <div class="mb-4">
                    <label for="password_reset" class="block text-gray-700 text-sm font-bold mb-2">Change Password:</label>
                    <input type="password" id="password_reset" name="password_reset" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="New Password">
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Confirm New Password">
                </div>
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Security Settings</button>
            </form>
        </div>
    </div>
@endsection