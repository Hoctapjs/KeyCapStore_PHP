@extends('layouts.admin')

@section('title', 'Dashboard - Thống kê')
@section('page-title', 'Dashboard - Thống kê')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4">📊 Dashboard - Thống kê kinh doanh</h2>

    {{-- Filter Section --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Chu kỳ</label>
                    <select name="period" class="form-select" id="period-select">
                        <option value="day" {{ $period == 'day' ? 'selected' : '' }}>Theo ngày</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Theo tuần</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Theo tháng</option>
                        <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Theo năm</option>
                        <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Tùy chỉnh</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter"></i> Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">💰 Tổng doanh thu</h6>
                    <h3 class="mb-0">{{ number_format($stats['total_revenue']) }}₫</h3>
                    <small>{{ $stats['total_orders'] }} đơn hàng</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-title">📦 Tổng nhập hàng</h6>
                    <h3 class="mb-0">{{ number_format($stats['total_purchase']) }}₫</h3>
                    <small>Chi phí nhập kho</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">📈 Lợi nhuận</h6>
                    <h3 class="mb-0">{{ number_format($stats['profit']) }}₫</h3>
                    <small>Doanh thu - Chi phí</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">🎯 Đơn trung bình</h6>
                    <h3 class="mb-0">{{ number_format($stats['avg_order_value']) }}₫</h3>
                    <small>Giá trị TB/đơn</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Biểu đồ Doanh thu & Nhập hàng</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="80"></canvas>
                    
                    {{-- Fallback: Hiển thị bảng nếu không có biểu đồ --}}
                    @if($revenueData->isEmpty())
                        <p class="text-muted text-center py-4">
                            <i class="bi bi-info-circle"></i> Không có dữ liệu doanh thu cho khoảng thời gian này.
                        </p>
                    @else
                        <div class="table-responsive mt-4">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kỳ</th>
                                        <th class="text-end">Doanh thu</th>
                                        <th class="text-end">Nhập hàng</th>
                                        <th class="text-end">Lợi nhuận</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($revenueData as $rev)
                                        @php
                                            $label = '';
                                            if ($period == 'day') {
                                                $label = $rev->date;
                                            } elseif ($period == 'week') {
                                                // Extract year and week from YEARWEEK format (202548 = year 2025, week 48)
                                                $yearweek = $rev->week;
                                                $year = intval(substr($yearweek, 0, 4));
                                                $week = intval(substr($yearweek, 4));
                                                $label = "Tuần $week/$year";
                                            } elseif ($period == 'month') {
                                                $label = $rev->month . '/' . $rev->year;
                                            } elseif ($period == 'year') {
                                                $label = $rev->year;
                                            }
                                            
                                            $purchase = $purchaseData->filter(function($p) use ($rev) {
                                                if ($period == 'month') {
                                                    return $p->month == $rev->month && $p->year == $rev->year;
                                                } elseif ($period == 'week') {
                                                    return $p->week == $rev->week;
                                                } elseif ($period == 'day') {
                                                    return $p->date == $rev->date;
                                                } elseif ($period == 'year') {
                                                    return $p->year == $rev->year;
                                                }
                                                return false;
                                            })->first();
                                            
                                            $purchaseCost = $purchase ? $purchase->total_cost : 0;
                                            $profit = $rev->revenue - $purchaseCost;
                                        @endphp
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td class="text-end text-success fw-bold">{{ number_format($rev->revenue) }}₫</td>
                                            <td class="text-end text-danger">{{ number_format($purchaseCost) }}₫</td>
                                            <td class="text-end {{ $profit >= 0 ? 'text-success' : 'text-danger' }} fw-bold">{{ number_format($profit) }}₫</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Monthly Stats --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">🏆 Thống kê Doanh thu (12 tháng)</h5>
                </div>
                <div class="card-body">
                    @if($monthlyStats['highest_revenue_month'])
                    <div class="mb-3">
                        <strong class="text-success">Cao nhất:</strong> 
                        Tháng {{ $monthlyStats['highest_revenue_month']->month }}/{{ $monthlyStats['highest_revenue_month']->year }}
                        - <span class="badge bg-success">{{ number_format($monthlyStats['highest_revenue_month']->revenue) }}₫</span>
                    </div>
                    @endif
                    @if($monthlyStats['lowest_revenue_month'])
                    <div>
                        <strong class="text-danger">Thấp nhất:</strong> 
                        Tháng {{ $monthlyStats['lowest_revenue_month']->month }}/{{ $monthlyStats['lowest_revenue_month']->year }}
                        - <span class="badge bg-danger">{{ number_format($monthlyStats['lowest_revenue_month']->revenue) }}₫</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">📦 Thống kê Nhập hàng (12 tháng)</h5>
                </div>
                <div class="card-body">
                    @if($monthlyStats['highest_purchase_month'])
                        <div class="mb-3">
                            <strong class="text-warning">Nhiều nhất:</strong> 
                            Tháng {{ $monthlyStats['highest_purchase_month']->month }}/{{ $monthlyStats['highest_purchase_month']->year }}
                            - <span class="badge bg-warning text-dark">{{ number_format($monthlyStats['highest_purchase_month']->total_cost) }}₫</span>
                        </div>
                    @endif
                    @if($monthlyStats['lowest_purchase_month'])
                        <div>
                            <strong class="text-info">Ít nhất:</strong> 
                            Tháng {{ $monthlyStats['lowest_purchase_month']->month }}/{{ $monthlyStats['lowest_purchase_month']->year }}
                            - <span class="badge bg-info">{{ number_format($monthlyStats['lowest_purchase_month']->total_cost) }}₫</span>
                        </div>
                    @else
                        <p class="text-muted">
                            <i class="bi bi-info-circle"></i>
                            Chưa có dữ liệu nhập hàng. Hãy nhập hàng và cập nhật giá nhập (unit_cost) để xem thống kê.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chuẩn bị dữ liệu cho chart
    const revenueData = @json($revenueData);
    const purchaseData = @json($purchaseData);
    const period = '{{ $period }}';

    console.log('Period:', period);
    console.log('Revenue Data:', revenueData);
    console.log('Purchase Data:', purchaseData);

    // Tạo labels và data
    let labels = [];
    let revenueValues = [];
    let purchaseValues = [];

    if (!revenueData || revenueData.length === 0) {
        // Nếu không có data, hiển thị thông báo
        const chartElement = document.getElementById('revenueChart');
        if (chartElement && chartElement.parentElement) {
            chartElement.parentElement.innerHTML = '<p class="text-muted text-center py-4">Không có dữ liệu doanh thu cho khoảng thời gian này</p>';
        }
        return;
    }

    revenueData.forEach(item => {
        let label = '';
        if (period === 'day') {
            label = item.date;
        } else if (period === 'week') {
            // Extract year and week from YEARWEEK format (202548 = year 2025, week 48)
            const yearweek = String(item.week);
            const year = yearweek.substring(0, 4);
            const week = yearweek.substring(4);
            label = `Tuần ${week}/${year}`;
        } else if (period === 'month') {
            label = `${String(item.month).padStart(2, '0')}/${item.year}`;
        } else if (period === 'year') {
            label = item.year;
        }
        
        labels.push(label);
        revenueValues.push(parseFloat(item.revenue) || 0);
    });

    console.log('Labels:', labels);
    console.log('Revenue Values:', revenueValues);

    // Match purchase data với labels
    purchaseValues = labels.map(label => {
        const matchedItem = purchaseData.find(item => {
            if (period === 'day') {
                return item.date === label;
            } else if (period === 'week') {
                // Extract year and week from YEARWEEK format
                const yearweek = String(item.week);
                const year = yearweek.substring(0, 4);
                const week = yearweek.substring(4);
                return `Tuần ${week}/${year}` === label;
            } else if (period === 'month') {
                return `${String(item.month).padStart(2, '0')}/${item.year}` === label;
            } else if (period === 'year') {
                return item.year == label;
            }
            return false;
        });
        return matchedItem ? parseFloat(matchedItem.total_cost) || 0 : 0;
    });

    console.log('Purchase Values:', purchaseValues);

    // Tạo chart
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        try {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Doanh thu (₫)',
                            data: revenueValues,
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.1,
                            fill: true,
                            borderWidth: 2
                        },
                        {
                            label: 'Nhập hàng (₫)',
                            data: purchaseValues,
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            tension: 0.1,
                            fill: true,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += new Intl.NumberFormat('vi-VN', {
                                        style: 'currency',
                                        currency: 'VND'
                                    }).format(context.parsed.y);
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', {
                                        notation: 'compact',
                                        compactDisplay: 'short'
                                    }).format(value) + '₫';
                                }
                            }
                        }
                    }
                }
            });
            console.log('Chart created successfully');
        } catch (error) {
            console.error('Error creating chart:', error);
        }
    } else {
        console.error('Chart canvas not found');
    }
});
</script>
@endpush
@endsection