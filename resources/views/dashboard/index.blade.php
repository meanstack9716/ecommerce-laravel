@extends('layouts.main')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-7xl mx-auto">
  <h1 class="text-2xl font-bold mb-6 text-gray-800">Sales Dashboard</h1>
  <!-- Overview Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-blue-100 p-4 rounded-lg shadow">
      <h2 class="text-lg font-semibold text-gray-700">Total Sales</h2>
      <p class="text-2xl font-bold text-blue-600">45,230</p>
      <p class="text-sm text-gray-500">This Month</p>
    </div>
    <div class="bg-green-100 p-4 rounded-lg shadow">
      <h2 class="text-lg font-semibold text-gray-700">Orders</h2>
      <p class="text-2xl font-bold text-green-600">2</p>
      <p class="text-sm text-gray-500">This Month</p>
    </div>
  </div>
  <!-- Charts Section -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Sales Over Time Chart -->
    <div class="bg-white p-4 rounded-lg shadow">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">Sales Over Time</h2>
      <canvas id="salesChart" class="w-full h-64"></canvas>
    </div>
    <!-- Top Products Chart -->
    <div class="bg-white p-4 rounded-lg shadow">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">Top Products</h2>
      <canvas id="productsChart" class="w-full h-64"></canvas>
    </div>
  </div>
  <!-- Sales Table -->
  <div class="bg-white p-4 rounded-lg shadow">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">Recent Sales</h2>
    <div class="overflow-x-auto">
      <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3">Order ID</th>
            <th scope="col" class="px-6 py-3">Customer</th>
            <th scope="col" class="px-6 py-3">Product</th>
            <th scope="col" class="px-6 py-3">Amount</th>
            <th scope="col" class="px-6 py-3">Date</th>
          </tr>
        </thead>
        <tbody>
          <tr class="bg-white border-b">
            <td class="px-6 py-4">#1001</td>
            <td class="px-6 py-4">Admin</td>
            <td class="px-6 py-4">Tshirt</td>
            <td class="px-6 py-4">45204</td>
            <td class="px-6 py-4">2025-05-17</td>
          </tr>
  
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Sales Over Time Chart
  const salesCtx = document.getElementById('salesChart').getContext('2d');
  new Chart(salesCtx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
      datasets: [{
        label: 'Sales ($)',
        data: [12000, 19000, 15000, 25000, 45230],
        borderColor: 'rgb(59, 130, 246)',
        backgroundColor: 'rgba(59, 130, 246, 0.2)',
        fill: true,
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Sales ($)' }
        },
        x: {
          title: { display: true, text: 'Month' }
        }
      }
    }
  });
  // Top Products Chart
  const productsCtx = document.getElementById('productsChart').getContext('2d');
  new Chart(productsCtx, {
    type: 'bar',
    data: {
      labels: ['Tshirt', 'Smartphone X', 'Headphones', 'Tablet'],
      datasets: [{
        label: 'Units Sold',
        data: [300, 450, 200, 150],
        backgroundColor: [
          'rgba(59, 130, 246, 0.8)',
          'rgba(34, 197, 94, 0.8)',
          'rgba(249, 115, 22, 0.8)',
          'rgba(139, 92, 246, 0.8)'
        ]
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Units Sold' }
        },
        x: {
          title: { display: true, text: 'Product' }
        }
      }
    }
  });
</script>
@endsection