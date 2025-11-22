@extends('layouts.app')

@section('title', 'BDM Dashboard - Konnectix')

@section('page-title', 'BDM Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body me-3">
                        <h2 class="fs-34 text-black font-w600">₹{{ number_format($monthlyAmount, 2) }}</h2>
                        <span class="fs-18">Monthly Amount</span>
                    </div>
                    <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 31C24.866 31 28 27.866 28 24C28 20.134 24.866 17 21 17C17.134 17 14 20.134 14 24C14 27.866 17.134 31 21 31Z" fill="#1EA7C5"/>
                        <path d="M31.5 31C35.366 31 38.5 27.866 38.5 24C38.5 20.134 35.366 17 31.5 17C27.634 17 24.5 20.134 24.5 24C24.5 27.866 27.634 31 31.5 31Z" fill="#1EA7C5" fill-opacity="0.5"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body me-3">
                        <h2 class="fs-34 text-black font-w600">{{ $monthlyInvoices }}</h2>
                        <span class="fs-18">Monthly Invoices</span>
                    </div>
                    <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 15H41V39H13V15Z" fill="#4CBC9A" fill-opacity="0.5"/>
                        <rect x="17" y="19" width="20" height="3" fill="#4CBC9A"/>
                        <rect x="17" y="26" width="20" height="3" fill="#4CBC9A"/>
                        <rect x="17" y="33" width="14" height="3" fill="#4CBC9A"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body me-3">
                        <h2 class="fs-34 text-black font-w600">₹{{ number_format($monthlyGST, 2) }}</h2>
                        <span class="fs-18">Monthly GST</span>
                    </div>
                    <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M27 10L40 18V36L27 44L14 36V18L27 10Z" fill="#FF9B52" fill-opacity="0.7"/>
                        <path d="M27 20V34" stroke="white" stroke-width="2"/>
                        <path d="M20 27H34" stroke="white" stroke-width="2"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body me-3">
                        <h2 class="fs-34 text-black font-w600">{{ $newCustomers }}</h2>
                        <span class="fs-18">New Customers</span>
                    </div>
                    <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="27" cy="20" r="6" fill="#FF5E5E" fill-opacity="0.7"/>
                        <path d="M15 38C15 32 20 28 27 28C34 28 39 32 39 38" stroke="#FF5E5E" stroke-width="3" fill="none"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Second Statistics Row -->
<div class="row">
    <div class="col-xl-6 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body me-3">
                        <h2 class="fs-34 text-black font-w600">₹{{ number_format($totalSalary, 2) }}</h2>
                        <span class="fs-18">Total Salary</span>
                    </div>
                    <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="12" y="18" width="30" height="20" rx="2" fill="#9568FF" fill-opacity="0.6"/>
                        <circle cx="27" cy="25" r="4" fill="#9568FF"/>
                        <path d="M27 29V33" stroke="#9568FF" stroke-width="2"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body me-3">
                        <h2 class="fs-34 text-black font-w600">₹{{ number_format($totalExpense, 2) }}</h2>
                        <span class="fs-18">Total Expense</span>
                    </div>
                    <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M27 14L40 22V38L27 46L14 38V22L27 14Z" fill="#FF6B6B" fill-opacity="0.6"/>
                        <path d="M27 24L27 34" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                        <path d="M22 29L32 29" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats Row -->
<div class="row">
    <div class="col-xl-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="media align-items-center">
                    <span class="p-3 me-3 feature-icon rounded">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 28C23.5228 28 28 23.5228 28 18C28 12.4772 23.5228 8 18 8C12.4772 8 8 12.4772 8 18C8 23.5228 12.4772 28 18 28Z" fill="#44814E"/>
                        </svg>
                    </span>
                    <div class="media-body">
                        <p class="fs-18 mb-2">Total Revenue</p>
                        <span class="fs-28 text-black font-w600">₹{{ number_format($totalRevenue, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="media align-items-center">
                    <span class="p-3 me-3 feature-icon rounded">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 28C23.5228 28 28 23.5228 28 18C28 12.4772 23.5228 8 18 8C12.4772 8 8 12.4772 8 18C8 23.5228 12.4772 28 18 28Z" fill="#3B4CB8"/>
                        </svg>
                    </span>
                    <div class="media-body">
                        <p class="fs-18 mb-2">Total Customers</p>
                        <span class="fs-28 text-black font-w600">{{ $totalCustomers }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="media align-items-center">
                    <span class="p-3 me-3 feature-icon rounded">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 28C23.5228 28 28 23.5228 28 18C28 12.4772 23.5228 8 18 8C12.4772 8 8 12.4772 8 18C8 23.5228 12.4772 28 18 28Z" fill="#F7931A"/>
                        </svg>
                    </span>
                    <div class="media-body">
                        <p class="fs-18 mb-2">Total Invoices</p>
                        <span class="fs-28 text-black font-w600">{{ $totalInvoices }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Tables Row -->
<div class="row">
    <!-- Revenue Chart -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">Revenue Trend (Last 6 Months)</h4>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">Quick Summary</h4>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-4">
                        <div class="bg-light p-3 rounded">
                            <h3 class="text-primary mb-1">{{ $monthlyInvoices }}</h3>
                            <p class="mb-0">Invoices This Month</p>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="bg-light p-3 rounded">
                            <h3 class="text-success mb-1">{{ $newCustomers }}</h3>
                            <p class="mb-0">New Customers</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-light p-3 rounded">
                            <h4 class="text-info mb-1">₹{{ number_format($monthlyAmount, 2) }}</h4>
                            <p class="mb-0">Monthly Revenue</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-block">
                        <i class="flaticon-381-plus me-2"></i>Create New Invoice
                    </a>
                    <a href="{{ route('customers.create') }}" class="btn btn-outline-primary btn-block mt-2">
                        <i class="flaticon-381-user-7 me-2"></i>Add New Customer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <!-- Recent Invoices -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">Recent Invoices</h4>
                <a href="{{ route('invoices.index') }}" class="btn btn-primary btn-sm">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInvoices as $invoice)
                            <tr>
                                <td>
                                    <a href="{{ route('invoices.show', $invoice) }}" class="text-primary">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td>{{ $invoice->customer->customer_name }}</td>
                                <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                                <td class="text-end"><strong>₹{{ number_format($invoice->grand_total, 2) }}</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No invoices yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Customers -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">Recent Customers</h4>
                <a href="{{ route('customers.index') }}" class="btn btn-primary btn-sm">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Phone</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCustomers as $customer)
                            <tr>
                                <td>
                                    <a href="{{ route('customers.edit', $customer) }}" class="text-primary">
                                        {{ $customer->customer_name }}
                                    </a>
                                </td>
                                <td>{{ $customer->company_name ?? '-' }}</td>
                                <td>{{ $customer->number }}</td>
                                <td>
                                    @if($customer->active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No customers yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Work Section -->
<div class="row mt-4">
    <!-- Upcoming Callbacks -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">
                    <i class="fa fa-phone text-primary me-2"></i>
                    Upcoming Callbacks
                </h4>
                <span class="badge badge-primary">{{ $upcomingCallbacks->count() }}</span>
            </div>
            <div class="card-body">
                @if($upcomingCallbacks->count() > 0)
                    <div class="upcoming-tasks">
                        @foreach($upcomingCallbacks as $callback)
                            <div class="task-item mb-3 p-3 border-start border-3 border-warning rounded">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <h6 class="mb-0 text-primary">{{ $callback->customer_name }}</h6>
                                            @if($callback->type === 'incoming')
                                                <span class="badge badge-info badge-sm"><i class="fa fa-arrow-down me-1"></i>Incoming</span>
                                            @else
                                                <span class="badge badge-warning badge-sm"><i class="fa fa-arrow-up me-1"></i>Outgoing</span>
                                            @endif
                                        </div>
                                        <p class="mb-1 text-muted">{{ $callback->phone_number }}</p>
                                        <small class="text-warning">
                                            <i class="fa fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($callback->callback_time)->format('d M Y, g:i A') }}
                                        </small>
                                        @if($callback->call_notes)
                                            <p class="text-muted small mt-1 mb-0">{{ $callback->call_notes }}</p>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column gap-1">
                                        <a href="tel:{{ $callback->phone_number }}" class="btn btn-sm btn-success">
                                            <i class="fa fa-phone"></i>
                                        </a>
                                        <button class="btn btn-sm btn-primary" onclick="markCallbackComplete({{ $callback->id }})">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fa fa-phone fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No upcoming callbacks</p>
                        <a href="{{ route('leads.all') }}" class="btn btn-primary btn-sm">Manage Leads</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Meetings -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">
                    <i class="fa fa-calendar text-success me-2"></i>
                    Upcoming Meetings
                </h4>
                <span class="badge badge-success">{{ $upcomingMeetings->count() }}/3 Daily</span>
            </div>
            <div class="card-body">
                @if($upcomingMeetings->count() > 0)
                    <div class="upcoming-tasks">
                        @foreach($upcomingMeetings as $meeting)
                            <div class="task-item mb-3 p-3 border-start border-3 border-success rounded">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h6 class="mb-0 text-success">{{ $meeting->customer_name }}</h6>
                                        @if($meeting->type === 'incoming')
                                            <span class="badge badge-info badge-sm"><i class="fa fa-arrow-down me-1"></i>Incoming</span>
                                        @else
                                            <span class="badge badge-warning badge-sm"><i class="fa fa-arrow-up me-1"></i>Outgoing</span>
                                        @endif
                                    </div>
                                    <p class="mb-1 text-muted">{{ $meeting->meeting_person_name }}</p>
                                    <small class="text-success d-block mb-2">
                                        <i class="fa fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($meeting->meeting_time)->format('d M Y, g:i A') }}
                                    </small>
                                    <p class="text-muted small mb-1">
                                        <i class="fa fa-map-marker-alt me-1"></i>{{ Str::limit($meeting->meeting_address, 50) }}
                                    </p>
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="tel:{{ $meeting->meeting_phone_number }}" class="btn btn-sm btn-success">
                                            <i class="fa fa-phone"></i> Call
                                        </a>
                                        <a href="{{ route('leads.show', $meeting) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fa fa-calendar fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No upcoming meetings</p>
                        <a href="{{ route('leads.all') }}" class="btn btn-success btn-sm">Schedule Meetings</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Did Not Receive Call List -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">
                    <i class="fa fa-phone-slash text-warning me-2"></i>
                    Did Not Receive Call List
                </h4>
                <span class="badge badge-warning">{{ $didNotReceiveList->count() }}</span>
            </div>
            <div class="card-body">
                @if($didNotReceiveList->count() > 0)
                    <div class="upcoming-tasks">
                        @foreach($didNotReceiveList as $lead)
                            <div class="task-item mb-3 p-3 border-start border-3 border-warning rounded">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <h6 class="mb-0 text-warning">{{ $lead->customer_name }}</h6>
                                            @if($lead->type === 'incoming')
                                                <span class="badge badge-info badge-sm"><i class="fa fa-arrow-down me-1"></i>Incoming</span>
                                            @else
                                                <span class="badge badge-warning badge-sm"><i class="fa fa-arrow-up me-1"></i>Outgoing</span>
                                            @endif
                                        </div>
                                        <p class="mb-1 text-muted">{{ $lead->phone_number }}</p>
                                        <small class="text-muted">
                                            <i class="fa fa-clock me-1"></i>
                                            Added: {{ $lead->updated_at->format('d M Y') }}
                                        </small>
                                        <p class="text-muted small mt-1 mb-0">{{ $lead->project_type }}</p>
                                    </div>
                                    <div class="d-flex flex-column gap-1">
                                        <a href="tel:{{ $lead->phone_number }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-phone"></i>
                                        </a>
                                        <button class="btn btn-sm btn-success" onclick="updateFinalResult({{ $lead->id }})">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fa fa-phone-slash fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No pending calls</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('template/vendor/chart.js/Chart.bundle.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyTrend = @json($monthlyTrend);
    
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyTrend.map(item => item.month),
                datasets: [{
                    label: 'Revenue (₹)',
                    data: monthlyTrend.map(item => item.amount),
                    borderColor: '#1EA7C5',
                    backgroundColor: 'rgba(30, 167, 197, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Invoices',
                    data: monthlyTrend.map(item => item.invoices),
                    borderColor: '#4CBC9A',
                    backgroundColor: 'rgba(76, 188, 154, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    if (context.datasetIndex === 0) {
                                        label += '₹' + context.parsed.y.toLocaleString('en-IN', {minimumFractionDigits: 2});
                                    } else {
                                        label += context.parsed.y;
                                    }
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString('en-IN');
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    }
});

// Mark callback as completed
function markCallbackComplete(leadId) {
    if (confirm('Mark this callback as completed?')) {
        fetch(`/leads/${leadId}/update-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: 'contacted',
                notes: 'Callback completed successfully'
            })
        })
        .then(response => {
            // Check if response is ok
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Response is not JSON');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Callback marked as completed!');
                location.reload();
            } else {
                alert('Error updating status: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status: ' + error.message);
        });
    }
}

// Update final result for did not receive calls
function updateFinalResult(leadId) {
    const result = prompt('Enter the final result after calling the customer:', 'Customer contacted successfully');
    
    if (result !== null && result.trim() !== '') {
        fetch(`/leads/${leadId}/update-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                status: 'contacted',
                notes: result
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Final result updated successfully!');
                location.reload();
            } else {
                alert('Error updating result: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the result.');
        });
    }
}
</script>
@endpush

@push('styles')
<style>
.badge-sm {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
}

.task-item {
    transition: all 0.2s ease;
}

.task-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.badge-info {
    background-color: #17a2b8;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}
</style>
@endpush
