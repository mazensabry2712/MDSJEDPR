@extends('layouts.master')
@section('css')
    <!--  Owl-carousel css-->
    <link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <!-- Maps css -->
    <link href="{{ URL::asset('assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <style>
        .sales-card {
            border-radius: 15px !important;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12) !important;
            margin-bottom: 20px;
        }

        .sales-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2) !important;
        }

        .stats-icon {
            font-size: 1.8rem;
            opacity: 0.25;
            position: absolute;
            right: 15px;
            top: 15px;
        }

        .card-content {
            position: relative;
            z-index: 2;
        }

        .stats-number {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
        }

        .stats-label {
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ========== FILTER SIDEBAR STYLES - MATCHING REPORTS ========== */
        .dashboard-filter-container {
            display: flex;
            gap: 25px;
            position: relative;
            margin-top: 0;
            width: 100%;
            padding: 0;
        }

        .filter-sidebar {
            width: 350px;
            flex-shrink: 0;
            height: fit-content;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 30px rgba(0, 123, 255, 0.15);
            border: 2px solid rgba(0, 123, 255, 0.15);
            position: relative;
        }

        .filter-sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
            border-radius: 15px 15px 0 0;
        }

        .filter-sidebar::-webkit-scrollbar {
            width: 8px;
        }

        .filter-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .filter-sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border-radius: 10px;
            transition: background 0.3s ease;
        }

        .filter-sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #0056b3 0%, #007bff 100%);
        }

        .sidebar-header {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 3px solid #007bff;
            position: relative;
        }

        .sidebar-header::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 60px;
            height: 3px;
            background: #0056b3;
        }

        .sidebar-header h5 {
            color: #007bff;
            font-weight: 800;
            font-size: 20px;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-header h5 i {
            color: #007bff;
            font-size: 22px;
            animation: filterPulse 2s ease-in-out infinite;
        }

        @keyframes filterPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .active-filters-badge {
            display: inline-block;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 10px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .active-filters-summary {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            border-left: 3px solid #007bff;
        }

        .active-filters-summary .badge {
            font-size: 10px;
            padding: 5px 10px;
            font-weight: 500;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
        }

        .filter-card {
            background: white;
            border: none;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .filter-card:hover {
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.15);
        }

        .filter-card .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            border-radius: 8px 8px 0 0;
            padding: 12px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-card .card-header:hover {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        }

        .filter-card .card-header h6 {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            color: #495057;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filter-card .card-header h6 span {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-card .card-header h6 i.fas {
            color: #007bff;
            font-size: 14px;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
            color: #007bff;
            font-size: 12px;
        }

        .collapsed .toggle-icon {
            transform: rotate(180deg);
        }

        .filter-card .card-body {
            padding: 15px;
        }

        .filter-card label {
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-card label i {
            color: #007bff;
            font-size: 11px;
        }

        .filter-card .form-control {
            background: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 6px;
            font-size: 13px;
            padding: 10px 12px;
            transition: all 0.3s ease;
        }

        .filter-card .form-control:hover {
            background: #ffffff;
            border-color: #e9ecef;
        }

        .filter-card .form-control:focus {
            background: white;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .select2-container--default .select2-selection--single {
            background: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 6px;
            height: 42px;
            padding: 6px 12px;
        }

        .select2-container--default .select2-selection--single:hover {
            background: #ffffff;
            border-color: #e9ecef;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            background: white;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .filter-actions {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 15px;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 15px;
            z-index: 10;
        }

        .btn-filter {
            width: 100%;
            margin-bottom: 10px;
            padding: 12px 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }

        .btn-apply-filter {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            color: white;
        }

        .btn-reset-filter {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            border: none;
            color: white;
        }

        .dashboard-content-area {
            flex: 1;
            min-width: 0;
        }

        .dashboard-content-area .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 25px rgba(0, 123, 255, 0.12);
            overflow: hidden;
        }

        .dashboard-content-area .card-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            padding: 20px 25px;
        }

        .dashboard-content-area .card-body {
            padding: 40px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .dashboard-filter-container {
                flex-direction: column;
            }

            .filter-sidebar {
                width: 100%;
                margin-bottom: 20px;
            }

            .sales-card {
                margin-bottom: 15px;
            }
        }

        /* Button hover effect */
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Hi, welcome back!</h2>
            </div>
        </div>
        {{-- <div class="main-dashboard-header-right">
						<div>
							<label class="tx-13">Customer Ratings</label>
							<div class="main-star">
								<i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star"></i> <span>(14,873)</span>
							</div>
						</div>
						<div>
							<label class="tx-13">Online Sales</label>
							<h5>563,275</h5>
						</div>
						<div>
							<label class="tx-13">Offline Sales</label>
							<h5>783,675</h5>
						</div>
					</div> --}}
    </div>
    <!-- /breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-users text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">👥 Users</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $userCount }}</h4>
                                    <p class="mb-0 tx-10 text-white op-7">Total System Users</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-danger-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-project-diagram text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">📊 Projects</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $projectcount }}</h4>
                                    <p class="mb-0 tx-10 text-white op-7">Active Projects</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-success-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-handshake text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">🤝 Customers</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $custCount }}</h4>
                                    <p class="mb-0 tx-10 text-white op-7">Total Customers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-warning-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-user-tie text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">💼 PMs</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $pmCount }}</h4>
                                    <p class="mb-0 tx-10 text-white op-7">Active PMs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-user-cog text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">⚙️ AMs</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $amCount }}</h4>
                                    <p class="mb-0 tx-10 text-white op-7">Active AMs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-danger-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-truck text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">🚛 Vendors</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $VendorsCount }}</h4>
                                    <p class="mb-0 tx-10 text-white op-7">Total Vendors</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-success-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-shipping-fast text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">📦 D/S</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $dsCount }}</h4>
                                    <p class="mb-0 tx-10 text-white op-7">Total Partners</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-warning-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-receipt text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">🧾 Invoices</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $invoiceCount }}
                                    </h4>
                                    <p class="mb-0 tx-10 text-white op-7">Total Invoices</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-info-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-file-alt text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">📄 DNs</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $dnCount }}
                                    </h4>
                                    <p class="mb-0 tx-10 text-white op-7">All DNs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-danger-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-certificate text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">📜 COCs</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $cocCount }}
                                    </h4>
                                    <p class="mb-0 tx-10 text-white op-7">Total COCs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-success-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-file-contract text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">📋 POs</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $posCount }}
                                    </h4>
                                    <p class="mb-0 tx-10 text-white op-7">Active POs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-warning-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-tasks text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">📊 Status</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $statusCount }}
                                    </h4>
                                    <p class="mb-0 tx-10 text-white op-7">Total Status</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- row 2 -->
    <div class="row row-sm">

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-clipboard-list text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">✅ Tasks</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $tasksCount }}
                                    </h4>
                                    <p class="mb-0 tx-10 text-white op-7">Active Tasks</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-danger-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-file-signature text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">📝 EPOs</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $epoCount }}
                                    </h4>
                                    <p class="mb-0 tx-10 text-white op-7">Total EPOs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-success-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-exclamation-triangle text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">⚠️ Risks</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $reskCount }}
                                    </h4>
                                    <p class="mb-0 tx-10 text-white op-7">Identified Risks</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card overflow-hidden sales-card bg-warning-gradient">
                <div class="pl-3 pt-2 pr-3 pb-2">
                    <i class="fas fa-flag-checkered text-white stats-icon" style="font-size: 1.8rem;"></i>
                    <div class="card-content">
                        <h6 class="mb-2 tx-11 text-white stats-label">🏁 Milestones</h6>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-18 font-weight-bold mb-0 text-white stats-number">{{ $milestonesCount }}
                                    </h4>
                                    <p class="mb-0 tx-10 text-white op-7">Achieved Goals</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->

    {{-- ========== FILTER SECTION WITH SIDEBAR ========== --}}
    <div class="row row-sm mt-4">
        <div class="col-12">
            <div class="dashboard-filter-container">
                {{-- Sidebar Filters --}}
                <div class="filter-sidebar">
                    {{-- Sidebar Header --}}
                    <div class="sidebar-header">
                        <h5>
                            <i class="fas fa-filter"></i>
                            Advanced Filters
                        </h5>
                    </div>

                    <form action="{{ route('dashboard.index') }}" method="GET" id="filterForm">
                        {{-- Filter 1: Project Information --}}
                        <div class="card filter-card">
                            <div class="card-header" data-toggle="collapse" data-target="#projectInfo">
                                <h6>
                                    <span><i class="fas fa-project-diagram"></i> Project Information</span>
                                    <i class="fas fa-chevron-up toggle-icon"></i>
                                </h6>
                            </div>
                            <div id="projectInfo" class="collapse show">
                                <div class="card-body">

                                    <!-- PR Number Filter -->

                                    <div class="form-group">
                                        <label><i class="fas fa-hashtag"></i> PR Number</label>
                                        <select name="filter[pr_number]" class="form-control select2"
                                            data-placeholder="-- Select PR Number --">
                                            <option></option>
                                            <option value="all"
                                                {{ request('filter.pr_number') == 'all' ? 'selected' : '' }}>All Projects
                                                ({{ $projectcount }})</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->pr_number }}"
                                                    {{ request('filter.pr_number') == $project->pr_number ? 'selected' : '' }}>
                                                    {{ $project->pr_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- PR Number without Invoices Filter -->

                                    <div class="form-group">
                                        <label><i class="fas fa-hashtag"></i> PR Number without Invoices</label>
                                        <select name="filter[pr_number_no_invoice]" class="form-control select2"
                                            data-placeholder="-- Select PR Number (No Invoices) --">
                                            <option></option>
                                            <option value="all"
                                                {{ request('filter.pr_number_no_invoice') == 'all' ? 'selected' : '' }}>All Projects
                                                ({{ $projectcount }})</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->pr_number }}"
                                                    {{ request('filter.pr_number_no_invoice') == $project->pr_number ? 'selected' : '' }}>
                                                    {{ $project->pr_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Project Name Filter -->
                                    {{-- <div class="form-group">
                                        <label><i class="fas fa-briefcase"></i> Project Name</label>
                                        <select name="filter[project_name]" class="form-control select2"
                                            data-placeholder="-- Select Project Name --">
                                            <option></option>
                                            <option value="all"
                                                {{ request('filter.project_name') == 'all' ? 'selected' : '' }}>All Projects
                                                ({{ $projectcount }})</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->name }}"
                                                    {{ request('filter.project_name') == $project->name ? 'selected' : '' }}>
                                                    {{ $project->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> --}}



                                </div>
                            </div>
                        </div>


                        {{-- Filter Actions --}}
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-filter btn-apply-filter">
                                <i class="fas fa-search"></i> Apply Filters
                            </button>
                            <button type="button" class="btn btn-filter btn-reset-filter" onclick="resetFilters()">
                                <i class="fas fa-undo"></i> Reset All
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Dashboard Content Area --}}
                <div class="dashboard-content-area">
                    @if ($hasFilters && $filteredProjects->count() > 0)
                        {{-- Project Details Section --}}
                        @foreach ($filteredProjects as $project)
                            <div class="row row-sm mb-4">
                                {{-- Project Information Card --}}
                                <div class="col-12 mb-4">
                                    <div class="card" style="border-radius: 15px; border: 3px solid #007bff; box-shadow: 0 6px 30px rgba(0, 123, 255, 0.2);">
                                        <div class="card-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); padding: 25px; display: flex; justify-content: space-between; align-items: center;">
                                            <h3 class="text-white mb-0">
                                                <i class="fas fa-project-diagram"></i> {{ $project->name }}
                                                <span class="badge badge-light ml-2" style="font-size: 14px;">PR# {{ $project->pr_number }}</span>
                                            </h3>
                                            {{-- Print & PDF Buttons --}}
                                            <div style="display: flex; gap: 10px;">
                                                {{-- Print Button --}}
                                                <form action="{{ route('dashboard.print.filtered') }}" method="GET" target="_blank" style="display: inline; margin: 0;">
                                                    @foreach(request('filter', []) as $key => $value)
                                                        @if($value)
                                                            <input type="hidden" name="filter[{{ $key }}]" value="{{ $value }}">
                                                        @endif
                                                    @endforeach
                                                    <button type="submit" class="btn"
                                                            style="background: white;
                                                                   color: #007bff;
                                                                   font-weight: 600;
                                                                   padding: 10px 20px;
                                                                   border-radius: 8px;
                                                                   box-shadow: 0 2px 8px rgba(255, 255, 255, 0.3);
                                                                   border: none;
                                                                   transition: all 0.3s ease;
                                                                   cursor: pointer;
                                                                   font-size: 14px;">
                                                        <i class="fas fa-print"></i> Print
                                                    </button>
                                                </form>

                                                {{-- PDF Button --}}
                                                <form action="{{ route('dashboard.pdf.filtered') }}" method="GET" target="_blank" style="display: inline; margin: 0;">
                                                    @foreach(request('filter', []) as $key => $value)
                                                        @if($value)
                                                            <input type="hidden" name="filter[{{ $key }}]" value="{{ $value }}">
                                                        @endif
                                                    @endforeach
                                                    <button type="submit" class="btn"
                                                            style="background: #dc3545;
                                                                   color: white;
                                                                   font-weight: 600;
                                                                   padding: 10px 20px;
                                                                   border-radius: 8px;
                                                                   box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
                                                                   border: none;
                                                                   transition: all 0.3s ease;
                                                                   cursor: pointer;
                                                                   font-size: 14px;">
                                                        <i class="fas fa-file-pdf"></i> PDF
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="card-body" style="padding: 30px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);">
                                            {{-- Project Info Grid --}}
                                            <div class="row mb-4">
                                                <div class="col-lg col-md-4 col-sm-6 mb-3">
                                                    <div class="info-box" style="background: white; padding: 20px; border-radius: 8px; border-top: 3px solid #667eea; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 100%; transition: all 0.3s;">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="fas fa-building" style="color: #667eea; font-size: 20px;"></i>
                                                            <small class="ml-2" style="color: #6c757d; font-weight: 600; font-size: 10px; text-transform: uppercase;">Customer</small>
                                                        </div>
                                                        <h5 class="mb-0" style="color: #2c3e50; font-weight: 600; font-size: 15px;">{{ $project->cust->name ?? 'N/A' }}</h5>
                                                    </div>
                                                </div>
                                                <div class="col-lg col-md-4 col-sm-6 mb-3">
                                                    <div class="info-box" style="background: white; padding: 20px; border-radius: 8px; border-top: 3px solid #28a745; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 100%; transition: all 0.3s;">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="fas fa-user-tie" style="color: #28a745; font-size: 20px;"></i>
                                                            <small class="ml-2" style="color: #6c757d; font-weight: 600; font-size: 10px; text-transform: uppercase;">PM</small>
                                                        </div>
                                                        <h5 class="mb-0" style="color: #2c3e50; font-weight: 600; font-size: 15px;">{{ $project->ppms->name ?? 'N/A' }}</h5>
                                                    </div>
                                                </div>
                                                @if(!request('filter.pr_number_no_invoice'))
                                                <div class="col-lg col-md-4 col-sm-6 mb-3">
                                                    <div class="info-box" style="background: white; padding: 20px; border-radius: 8px; border-top: 3px solid #ffc107; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 100%; transition: all 0.3s;">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="fas fa-dollar-sign" style="color: #ffc107; font-size: 20px;"></i>
                                                            <small class="ml-2" style="color: #6c757d; font-weight: 600; font-size: 10px; text-transform: uppercase;">Value</small>
                                                        </div>
                                                        <h5 class="mb-0" style="color: #2c3e50; font-weight: 600; font-size: 15px;">{{ number_format($project->value ?? 0, 0) }} SAR</h5>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="col-lg col-md-4 col-sm-6 mb-3">
                                                    <div class="info-box" style="background: white; padding: 20px; border-radius: 8px; border-top: 3px solid #17a2b8; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 100%; transition: all 0.3s;">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="fas fa-calendar-alt" style="color: #17a2b8; font-size: 20px;"></i>
                                                            <small class="ml-2" style="color: #6c757d; font-weight: 600; font-size: 10px; text-transform: uppercase;">PO Date</small>
                                                        </div>
                                                        <h5 class="mb-0" style="color: #2c3e50; font-weight: 600; font-size: 15px;">{{ $project->customer_po_date ?? 'N/A' }}</h5>
                                                    </div>
                                                </div>
                                                <div class="col-lg col-md-4 col-sm-6 mb-3">
                                                    <div class="info-box" style="background: white; padding: 20px; border-radius: 8px; border-top: 3px solid #6f42c1; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 100%; transition: all 0.3s;">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="fas fa-code" style="color: #6f42c1; font-size: 20px;"></i>
                                                            <small class="ml-2" style="color: #6c757d; font-weight: 600; font-size: 10px; text-transform: uppercase;">Technologies</small>
                                                        </div>
                                                        <h5 class="mb-0" style="color: #2c3e50; font-weight: 600; font-size: 15px;">{{ $project->technologies ?? 'N/A' }}</h5>
                                                    </div>
                                                </div>
                                            </div>



                                            @php
                                                // Calculate task statistics
                                                $totalTasks = $project->tasks->count();
                                                $completedTasks = $project->tasks->whereIn('status', ['Completed', 'completed', 'Done', 'done'])->count();
                                                $pendingTasks = $project->tasks->whereIn('status', ['Pending', 'pending', 'In Progress', 'in progress'])->count();
                                                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;
                                            @endphp

                                            <div id="progress-section-{{ $project->pr_number }}" class="mb-4" style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); padding: 25px; border-radius: 15px; box-shadow: 0 3px 20px rgba(0,0,0,0.1); border: 1px solid #e9ecef;">
                                                <div class="d-flex justify-content-between align-items-center mb-4">
                                                    <div>
                                                        <h5 class="mb-1" style="color: #2c3e50; font-weight: 700;">
                                                            <i class="fas fa-chart-line" style="color: #28a745;"></i> Project Progress
                                                        </h5>
                                                        <small class="text-muted">Task completion status</small>
                                                    </div>
                                                    <div class="text-right d-flex align-items-center" style="gap: 10px;">
                                                        <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                                                                    color: white;
                                                                    font-size: 24px;
                                                                    font-weight: 700;
                                                                    padding: 12px 24px;
                                                                    border-radius: 12px;
                                                                    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
                                                                    min-width: 100px;
                                                                    text-align: center;">
                                                            {{ $progress }}%
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Progress Bar - Always Show --}}
                                                <div style="background: #e9ecef;
                                                            height: 30px;
                                                            border-radius: 15px;
                                                            overflow: hidden;
                                                            position: relative;
                                                            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                                                    <div style="background: linear-gradient(90deg, #28a745 0%, #34ce57 100%);
                                                                height: 100%;
                                                                width: {{ $progress }}%;
                                                                border-radius: 15px;
                                                                transition: width 0.6s ease;
                                                                position: relative;
                                                                box-shadow: 0 2px 8px rgba(40, 167, 69, 0.4);">
                                                    </div>
                                                </div>

                                                {{-- Expected Completion Date --}}
                                                <div class="mt-3 mb-3" style="background: white; padding: 15px 20px; border-radius: 10px; border-left: 4px solid #17a2b8; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-calendar-check" style="color: #17a2b8; font-size: 20px; margin-right: 12px;"></i>
                                                        <div>
                                                            <small style="color: #6c757d; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Expected Completion Date</small>
                                                            <h6 class="mb-0 mt-1" style="color: #2c3e50; font-weight: 600; font-size: 16px;">{{ $project->customer_po_deadline ?? 'Not Set' }}</h6>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Completed and Total Boxes - Always Show --}}
                                                <div class="row mt-4">
                                                    <div class="col-6">
                                                        <div style="background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
                                                                    padding: 20px;
                                                                    border-radius: 12px;
                                                                    border-left: 4px solid #ffc107;
                                                                    box-shadow: 0 2px 10px rgba(255, 193, 7, 0.15);">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div>
                                                                    <div style="color: #856404; font-size: 13px; font-weight: 600; margin-bottom: 5px;">
                                                                        <i class="fas fa-clock"></i> PENDING
                                                                    </div>
                                                                    <div style="color: #ffc107; font-size: 32px; font-weight: 700;">
                                                                        {{ $pendingTasks }}
                                                                    </div>
                                                                </div>
                                                                <i class="fas fa-hourglass-half" style="font-size: 40px; color: #ffc107; opacity: 0.2;"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div style="background: linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%);
                                                                    padding: 20px;
                                                                    border-radius: 12px;
                                                                    border-left: 4px solid #6c757d;
                                                                    box-shadow: 0 2px 10px rgba(108, 117, 125, 0.15);">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div>
                                                                    <div style="color: #495057; font-size: 13px; font-weight: 600; margin-bottom: 5px;">
                                                                        <i class="fas fa-list"></i> TOTAL TASKS
                                                                    </div>
                                                                    <div style="color: #495057; font-size: 32px; font-weight: 700;">
                                                                        {{ $totalTasks }}
                                                                    </div>
                                                                </div>
                                                                <i class="fas fa-tasks" style="font-size: 40px; color: #6c757d; opacity: 0.2;"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Statistics Cards --}}
                                            <div class="row">
                                                {{-- Tasks Statistics --}}
                                                <div class="col-md-4 col-sm-6 mb-3">
                                                    <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div style="width: 100%;">
                                                                <small style="opacity: 0.9; font-weight: 600; font-size: 12px;">Tasks</small>
                                                                <div style="font-size: 13px; line-height: 1.6; max-height: 100px; overflow-y: auto; margin-top: 10px;">
                                                                    @php
                                                                        $pendingTasks = $project->tasks->whereIn('status', ['Pending', 'pending', 'In Progress', 'in progress']);
                                                                    @endphp
                                                                    @if($pendingTasks->count() > 0)
                                                                        @foreach($pendingTasks as $task)
                                                                            <div style="padding: 6px 0; margin-bottom: 4px; border-bottom: 1px solid rgba(255,255,255,0.15);">
                                                                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 6px;">
                                                                                    <span style="font-weight: 600; flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $task->details ?? 'Task' }}</span>
                                                                                    <i class="fas fa-long-arrow-alt-right" style="font-size: 11px; opacity: 0.7; flex-shrink: 0;"></i>
                                                                                    <span style="background: rgba(255,255,255,0.3); padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; flex-shrink: 0; white-space: nowrap;">{{ $task->assigned ?? 'N/A' }}</span>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        <div style="opacity: 0.7; padding: 10px 0;">No pending tasks</div>
                                                                    @endif
                                                                </div>
                                                                <small style="opacity: 0.85; display: block; margin-top: 10px; font-weight: 600;">{{ $project->tasks->whereIn('status', ['Pending', 'pending', 'In Progress', 'in progress'])->count() }}/{{ $project->tasks->count() }} Pending</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Risks Statistics --}}
                                                <div class="col-md-4 col-sm-6 mb-3">
                                                    <div class="stat-card" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div style="width: 100%;">
                                                                <small style="opacity: 0.9; font-weight: 600; font-size: 12px;">Risk/Issue</small>
                                                                <div style="font-size: 13px; line-height: 1.6; max-height: 100px; overflow-y: auto; margin-top: 10px;">
                                                                    @if($project->risks->count() > 0)
                                                                        @foreach($project->risks as $risk)
                                                                            <div style="padding: 6px 0; margin-bottom: 4px; border-bottom: 1px solid rgba(255,255,255,0.15);">
                                                                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 6px;">
                                                                                    <span style="font-weight: 600; flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $risk->risk }}</span>
                                                                                    <i class="fas fa-long-arrow-alt-right" style="font-size: 11px; opacity: 0.7; flex-shrink: 0;"></i>
                                                                                    <span style="background: rgba(255,255,255,0.3); padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; flex-shrink: 0; white-space: nowrap;">{{ $risk->impact ?? 'N/A' }}</span>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        <div style="opacity: 0.7; padding: 10px 0;">No risks</div>
                                                                    @endif
                                                                </div>
                                                                <small style="opacity: 0.85; display: block; margin-top: 10px; font-weight: 600;">{{ $project->risks->whereIn('status', ['closed'])->count() }}/{{ $project->risks->count() }} Closed</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Milestones Statistics --}}
                                                <div class="col-md-4 col-sm-6 mb-3">
                                                    <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div style="width: 100%;">
                                                                <small style="opacity: 0.9; font-weight: 600; font-size: 12px;">Milestones</small>
                                                                <div style="font-size: 13px; line-height: 1.6; max-height: 100px; overflow-y: auto; margin-top: 10px;">
                                                                    @if($project->milestones->count() > 0)
                                                                        @foreach($project->milestones as $milestone)
                                                                            <div style="padding: 6px 0; margin-bottom: 4px; border-bottom: 1px solid rgba(255,255,255,0.15);">
                                                                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 6px;">
                                                                                    <span style="font-weight: 600; flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $milestone->milestone }}</span>
                                                                                    <i class="fas fa-long-arrow-alt-right" style="font-size: 11px; opacity: 0.7; flex-shrink: 0;"></i>
                                                                                    <span style="background: rgba(255,255,255,0.3); padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; flex-shrink: 0; white-space: nowrap;">{{ $milestone->status ?? 'N/A' }}</span>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        <div style="opacity: 0.7; padding: 10px 0;">No milestones</div>
                                                                    @endif
                                                                </div>
                                                                <small style="opacity: 0.85; display: block; margin-top: 10px; font-weight: 600;">{{ $project->milestones->whereIn('status', ['Completed', 'completed', 'on track'])->count() }}/{{ $project->milestones->count() }} Done</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Invoices Statistics --}}
                                                @if(!request('filter.pr_number_no_invoice'))
                                                <div class="col-md-4 col-sm-6 mb-3">
                                                    <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div style="width: 100%;">
                                                                <small style="opacity: 0.9; font-weight: 600; font-size: 12px;">Invoices</small>
                                                                <div style="font-size: 13px; line-height: 1.6; max-height: 100px; overflow-y: auto; margin-top: 10px;">
                                                                    @if($project->invoices->count() > 0)
                                                                        @foreach($project->invoices as $invoice)
                                                                            <div style="padding: 6px 0; margin-bottom: 4px; border-bottom: 1px solid rgba(255,255,255,0.15);">
                                                                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 6px;">
                                                                                    <span style="font-weight: 600; flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $invoice->invoice_number ?? 'N/A' }}</span>
                                                                                    <i class="fas fa-long-arrow-alt-right" style="font-size: 11px; opacity: 0.7; flex-shrink: 0;"></i>
                                                                                    <span style="background: rgba(255,255,255,0.3); padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; flex-shrink: 0; white-space: nowrap;">{{ number_format($invoice->value ?? 0, 0) }} SAR</span>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        <div style="opacity: 0.7; padding: 10px 0;">No invoices</div>
                                                                    @endif
                                                                </div>
                                                                <small style="opacity: 0.85; display: block; margin-top: 10px; font-weight: 600;">{{ $project->invoices->whereIn('status', ['paid', 'Paid'])->count() }}/{{ $project->invoices->count() }} Paid</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                {{-- DN Statistics --}}
                                                <div class="col-md-4 col-sm-6 mb-3">
                                                    <div class="stat-card" style="background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(111, 66, 193, 0.3);">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div style="width: 100%;">
                                                                <small style="opacity: 0.9; font-weight: 600; font-size: 12px;">DNs</small>
                                                                <div style="margin-top: 10px;">
                                                                    @if($project->dns->count() > 0)
                                                                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                                                            @foreach($project->dns as $dn)
                                                                                <div style="background: rgba(255,255,255,0.2); padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 13px; flex: 0 0 calc(25% - 6px); text-align: center;">
                                                                                    {{ $dn->dn_number ?? 'N/A' }}
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <div style="opacity: 0.7; padding: 10px 0;">No DNs</div>
                                                                    @endif
                                                                </div>
                                                                <small style="opacity: 0.85; display: block; margin-top: 10px; font-weight: 600;">{{ $project->dns->count() }} Total</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Escalation (Customer Contact - AM) --}}
                                                <div class="col-md-4 col-sm-6 mb-3">
                                                    <div class="stat-card" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div style="width: 100%;">
                                                                <small style="opacity: 0.9; font-weight: 600; font-size: 12px;">Escalation</small>
                                                                <div style="font-size: 13px; line-height: 1.6; max-height: 100px; overflow-y: auto; margin-top: 10px;">
                                                                    @if($project->customer_contact_details || $project->aams)
                                                                        <div style="padding: 6px 0; margin-bottom: 4px; border-bottom: 1px solid rgba(255,255,255,0.15);">
                                                                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 6px;">
                                                                                <span style="font-weight: 600; flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $project->customer_contact_details ?? 'N/A' }}</span>
                                                                                <i class="fas fa-long-arrow-alt-right" style="font-size: 11px; opacity: 0.7; flex-shrink: 0;"></i>
                                                                                <span style="background: rgba(255,255,255,0.3); padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; flex-shrink: 0; white-space: nowrap;">{{ $project->aams->name ?? 'N/A' }}</span>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div style="opacity: 0.7; padding: 10px 0; text-align: center;">No contact info</div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- No Filters Applied Message --}}
                        <div class="row row-sm">
                            <div class="col-12">
                                <div class="card"
                                    style="border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 123, 255, 0.15);">
                                    <div class="card-header"
                                        style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border-radius: 15px 15px 0 0;">
                                        <h4 class="card-title text-white mb-0">
                                            <i class="fas fa-chart-line"></i> Filtered Dashboard Data
                                        </h4>
                                    </div>
                                    <div class="card-body" style="min-height: 400px;">
                                        <div class="text-center py-5">
                                            <i class="fas fa-chart-bar"
                                                style="font-size: 4rem; color: #007bff; opacity: 0.3;"></i>
                                            <h5 class="mt-4" style="color: #6c757d;">Apply filters to view customized
                                                data</h5>
                                            <p class="text-muted">Use the filters on the left to narrow down your dashboard
                                                view</p>
                                            <div class="mt-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle"></i>
                                                    Select filters and click "Apply Filters" to see results
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Filter section closed -->
    </div>
    </div>
    <!-- Container closed -->
@endsection
@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Moment js -->
    <script src="{{ URL::asset('assets/plugins/raphael/raphael.min.js') }}"></script>
    <!--Internal  Flot js-->
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.categories.js') }}"></script>
    <script src="{{ URL::asset('assets/js/dashboard.sampledata.js') }}"></script>
    <script src="{{ URL::asset('assets/js/chart.flot.sampledata.js') }}"></script>
    <!--Internal Apexchart js-->
    <script src="{{ URL::asset('assets/js/apexcharts.js') }}"></script>
    <!-- Internal Map -->
    <script src="{{ URL::asset('assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ URL::asset('assets/js/modal-popup.js') }}"></script>
    <!--Internal  index js -->
    <script src="{{ URL::asset('assets/js/index.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.vmap.sampledata.js') }}"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'default',
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true
            });

            // Toggle collapse icons
            $('.card-header[data-toggle="collapse"]').on('click', function() {
                $(this).find('.toggle-icon').toggleClass('collapsed');
            });

            // Smooth scroll animation for filter sidebar
            $('.filter-sidebar').on('scroll', function() {
                var scrollTop = $(this).scrollTop();
                if (scrollTop > 50) {
                    $(this).addClass('scrolled');
                } else {
                    $(this).removeClass('scrolled');
                }
            });
        });

        // Reset Filters Function
        function resetFilters() {
            // Show loading state
            const resetBtn = event.target.closest('button');
            const originalHtml = resetBtn.innerHTML;
            resetBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Resetting...';
            resetBtn.disabled = true;

            // Clear all select2 selections
            $('.select2').val(null).trigger('change');

            // Clear all form inputs
            $('#filterForm')[0].reset();

            // Clear text inputs specifically
            $('#filterForm input[type="text"]').val('');
            $('#filterForm input[type="number"]').val('');

            // Redirect to dashboard without filters
            setTimeout(function() {
                window.location.href = '{{ route('dashboard.index') }}';
            }, 300);
        }

        // Form submission with loading indicator
        $('#filterForm').on('submit', function(e) {
            // Show loading state
            $('.btn-apply-filter').html('<i class="fas fa-spinner fa-spin"></i> Applying...').prop('disabled',
            true);
        });

        // Collapse all filters function
        function collapseAllFilters() {
            $('.collapse').collapse('hide');
        }

        // Expand all filters function
        function expandAllFilters() {
            $('.collapse').collapse('show');
        }
    </script>
@endsection
