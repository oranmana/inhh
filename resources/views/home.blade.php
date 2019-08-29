@extends('layouts.app')

@section('content')
@php($uempid = Auth::user()->empid)
<div class="container">
    <div class="row mt-5">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                @can('isMaster')
                <h3>Master
                @else   
                    @can('isAdmin')
                    <h3>Admin
                    @else
                        @can('isPP')
                        <h3>President
                        @else
                            @can('isDM')
                            <h3>DM
                            @else
                                @can('isTM')
                                <h3>TM
                                @else
                                <h3>No 
                                @endcan
                            @endcan
                        @endcan
                    @endcan
                @endcan

                @can('isHR')
                    - HR
                @endcan
                @can('isSD')
                    - SD
                @endcan
                Authorized </h3>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <ul>
                        @can('isAdmin')
                       <li><a href="/commons">Common</a></li>
                       <li><a href="/emps">Employee</a></li>
                       <li><a href="/dirs">Directory</a><hr></li>
                       @endcan
                       
                    @can('isHR')
                        @can('isTM')
                            <li><a href="calendars">Org Calendar</a></li>
                            <li><a href="/orgs">Organization Unit Management</a></li>
                            <li><a href="/jobs">Job Title Management</a>
                            <hr>
                            </li>
                        @endcan
                        <li><a href="/hrrq">HR Request</a></li>
                        <li><a href="/hrcons">Employment Contract</a><hr></li>
                        <li><a href="/emps">Employee Profiles</a></li>
                        <li><a href="/training">Training Center</a></li>
                    @endcan
                    <li><a href="/daily">Daily Attendance</a></li>
                    <li><a href="/otorder">OT Order</a><hr></li>
                    @can('isTM')
                    <li><a href="/leaveitem">Leave Profile</a></li>
                    @endcan
                    <li><a href="/leave">Leave Request</a><hr></li>
                    @can('isPayroll')
                        @can('isTM')
                        <li><a href="/payitems">Payroll Items</a></li>
                        @endcan
                        <li><a href="/payrolls">Payroll</a><hr></li>
                    @endcan
                    @can('isHR')
                        @can('isTM')
                    <li><a href="/doctypes">Document Type</a></li>
                        @endcan
                    @endcan
                    <li><a href="/doc/list">Document Center</a><hr></li>
                    @can('isHR')
                        @can('isTM')
                    <li><a href="/assetgroups">Asset Group</a></li>
                        @endcan
                    @endcan
                    <li><a href="/assets/0/0/{{ $uempid }}">Asset Library</a><hr></li>
                    @can('isSD')
                        @can('isTM')
                        <li><a href="/products">Sale Product</a></li>
                        <li><a href="/countries">Sale Countries</a></li>
                        @endcan
                        <li><a href="/points">Point ID</a></li>
                        <li><a href="/sales">Sale Support Center</a><hr></li>
                    @endcan
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
