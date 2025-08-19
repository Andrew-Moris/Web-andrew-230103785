@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-gray-900 p-6 rounded-lg shadow-lg mb-8">
        <h1 class="text-3xl font-bold text-white mb-6">Facebook Ads Purchase & Leads Breakdown</h1>

        <!-- Top Row Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold text-white mb-4">Amount Spent</h2>
                <p class="text-gray-300 text-4xl font-bold">${{ number_format($amountSpent, 2) }}</p>
                <p class="text-green-400">▲ 12% <span class="text-gray-500">vs 30 days ago ($2,000.00)</span></p>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold text-white mb-4">Purchase ROAS (Return on Ad Spend)</h2>
                <p class="text-gray-300 text-4xl font-bold">{{ $purchaseRoas }}</p>
                <p class="text-red-400">▼ 16% <span class="text-gray-500">vs 30 days ago (263)</span></p>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold text-white mb-4">Frequency</h2>
                <p class="text-gray-300 text-4xl font-bold">{{ number_format($frequency, 1) }}</p>
                <p class="text-red-400">▼ 60% <span class="text-gray-500">vs 30 days ago (5.0)</span></p>
            </div>
        </div>

        <!-- Middle Row Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold text-white mb-4">Purchases</h2>
                <p class="text-gray-300 text-4xl font-bold">{{ $purchases }}</p>
                <p class="text-red-400">▼ 53% <span class="text-gray-500">vs 30 days ago (424)</span></p>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold text-white mb-4">Cost Per Lead</h2>
                <p class="text-gray-300 text-4xl font-bold">{{ $costPerLead }}</p>
                <p class="text-green-400">▲ 187% <span class="text-gray-500">vs 30 days ago (151)</span></p>
            </div>
        </div>

        <!-- Campaign Performance Table -->
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold text-white mb-4">Campaign Performance Overview</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">Campaign</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">Link Clicks</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">Reach</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">Clicks</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">Leads</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">Purchases</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">CTR (by Campaign)</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">Purchase CV</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">Purchase ROAS</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-400">Cost Per Lead</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($campaignPerformance as $campaign)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-white">{{ $campaign['campaign'] }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-300">{{ $campaign['linkClicks'] }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-300">{{ $campaign['reach'] }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-300">{{ $campaign['clicks'] }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-300">{{ $campaign['leads'] }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-300">{{ $campaign['purchases'] }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-300">{{ $campaign['ctr'] }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-300">{{ $campaign['purchaseCv'] }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-300">{{ $campaign['purchaseRoas'] }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-300">{{ $campaign['costPerLead'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>


@endsection