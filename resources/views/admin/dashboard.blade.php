<x-cms-dashboard::layouts.admin title="Dashboard">
    <style>
        .classic-card {
            background: #fff;
            border: 1px solid #c3c4c7;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
            margin-bottom: 20px;
        }
        .classic-card-header {
            padding: 10px 15px;
            border-bottom: 1px solid #f0f0f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .classic-card-title {
            font-size: 14px;
            font-weight: 600;
            color: #1d2327;
        }
        .classic-stat-box {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .classic-stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        .classic-stat-value {
            font-size: 21px;
            font-weight: 700;
            color: #1d2327;
            line-height: 1.2;
        }
        .classic-stat-label {
            font-size: 13px;
            color: #646970;
            font-weight: 500;
        }
    </style>

    <div class="p-4 sm:p-6 bg-[#f0f0f1] min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-[23px] font-normal text-[#1d2327]">Dashboard</h1>
            <nav class="text-[13px] text-[#646970]">
                Home / Dashboard
            </nav>
        </div>

        <!-- Info Boxes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
            <div class="classic-card">
                <div class="classic-stat-box">
                    <div class="classic-stat-icon bg-[#2271b1]">
                        <span class="material-symbols-outlined text-[24px]">article</span>
                    </div>
                    <div>
                        <div class="classic-stat-value">{{ $stats['total_posts']['count'] }}</div>
                        <div class="classic-stat-label">Total Posts</div>
                    </div>
                </div>
            </div>
            <div class="classic-card">
                <div class="classic-stat-box">
                    <div class="classic-stat-icon bg-[#46b450]">
                        <span class="material-symbols-outlined text-[24px]">description</span>
                    </div>
                    <div>
                        <div class="classic-stat-value">{{ $stats['total_pages']['count'] }}</div>
                        <div class="classic-stat-label">Total Pages</div>
                    </div>
                </div>
            </div>
            <div class="classic-card">
                <div class="classic-stat-box">
                    <div class="classic-stat-icon bg-[#d63638]">
                        <span class="material-symbols-outlined text-[24px]">group</span>
                    </div>
                    <div>
                        <div class="classic-stat-value">{{ $stats['total_users']['count'] }}</div>
                        <div class="classic-stat-label">Total Users</div>
                    </div>
                </div>
            </div>
            <div class="classic-card">
                <div class="classic-stat-box">
                    <div class="classic-stat-icon bg-[#dba617]">
                        <span class="material-symbols-outlined text-[24px]">block</span>
                    </div>
                    <div>
                        <div class="classic-stat-value">{{ $stats['blacklisted_ips']['count'] }}</div>
                        <div class="classic-stat-label">Blocked IPs</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Chart -->
            <div class="lg:col-span-2">
                <div class="classic-card">
                    <div class="classic-card-header">
                        <span class="classic-card-title">Activity Overview</span>
                        <span class="text-[12px] text-[#646970]">Last 7 Months</span>
                    </div>
                    <div class="p-4">
                        <div class="h-[300px]">
                            <canvas id="impressionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- At a Glance / Right Sidebar -->
            <div class="lg:col-span-1">
                <div class="classic-card">
                    <div class="classic-card-header">
                        <span class="classic-card-title">At a Glance</span>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="flex items-center justify-between border-b border-[#f0f0f1] pb-3">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[#2271b1] text-[20px]">movie</span>
                                <span class="text-[13px] font-medium">Media Assets</span>
                            </div>
                            <span class="font-bold text-[#1d2327]">{{ $stats['media_count']['count'] }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-[#f0f0f1] pb-3">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[#d63638] text-[20px]">person_off</span>
                                <span class="text-[13px] font-medium">Blocked Accounts</span>
                            </div>
                            <span class="font-bold text-[#d63638]">{{ $stats['blocked_users']['count'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[#46b450] text-[20px]">trending_up</span>
                                <span class="text-[13px] font-medium">Conversion Rate</span>
                            </div>
                            <span class="font-bold text-[#46b450]">{{ $stats['traffic_stats']['conversion_rate']['value'] }}</span>
                        </div>
                    </div>
                    <div class="bg-[#f6f7f7] p-3 text-center border-t border-[#c3c4c7]">
                        <a href="{{ route('admin.posts.index') }}" class="text-[#2271b1] text-[12px] font-semibold hover:underline">View All Posts</a>
                    </div>
                </div>

                <!-- Security Status Box -->
                <div class="classic-card">
                    <div class="classic-card-header">
                        <span class="classic-card-title">Security Status</span>
                        @php $sec = $stats['traffic_stats']['security'] ?? ['status' => 'Healthy', 'message' => 'System protection is active.']; @endphp
                        <span class="px-2 py-0.5 {{ $sec['status'] === 'Healthy' ? 'bg-[#46b450]' : 'bg-[#d63638]' }} text-white text-[10px] rounded font-bold uppercase">
                            {{ $sec['status'] }}
                        </span>
                    </div>
                    <div class="p-4">
                        <p class="text-[13px] text-[#646970] leading-relaxed">
                            {{ $sec['message'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script src="{{ asset('vendor/cms-dashboard/js/chart.min.js') }}"></script>
    <script>
        const ctx = document.getElementById('impressionChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($stats['traffic_stats']['labels']) !!},
                datasets: [{
                    label: 'Impressions',
                    data: {!! json_encode($stats['traffic_stats']['impressions']) !!},
                    borderColor: '#2271b1',
                    backgroundColor: 'rgba(34, 113, 177, 0.05)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#2271b1'
                }, {
                    label: 'Visitors',
                    data: {!! json_encode($stats['traffic_stats']['visitors']) !!},
                    borderColor: '#c3c4c7',
                    borderDash: [5, 5],
                    fill: false,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 11 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f1' },
                        ticks: { font: { size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    </script>
    @endpush
</x-cms-dashboard::layouts.admin>
