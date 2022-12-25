@extends('backend.layouts.master')

@section('title')
    @include('larapoll::dashboard.partials.title')
@endsection

@section('styles')
    <!-- Styles -->
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">

    <!--Regular Datatables CSS-->
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <!--Responsive Extension Datatables CSS-->
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">

    <style>

        /*Overrides for Tailwind CSS */

        /*Form fields*/
        .dataTables_wrapper select,
        .dataTables_wrapper .dataTables_filter input {
            color: #4a5568; /*text-gray-700*/
            padding-left: 1rem; /*pl-4*/
            padding-right: 1rem; /*pl-4*/
            padding-top: .5rem; /*pl-2*/
            padding-bottom: .5rem; /*pl-2*/
            line-height: 1.25; /*leading-tight*/
            border-width: 2px; /*border-2*/
            border-radius: .25rem;
            border-color: #edf2f7; /*border-gray-200*/
            background-color: #edf2f7; /*bg-gray-200*/
        }

        /*Row Hover*/
        table.dataTable.hover tbody tr:hover, table.dataTable.display tbody tr:hover {
            background-color: #ebf4ff; /*bg-indigo-100*/
        }

        /*Pagination Buttons*/
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            font-weight: 700; /*font-bold*/
            border-radius: .25rem; /*rounded*/
            border: 1px solid transparent; /*border border-transparent*/
        }

        /*Pagination Buttons - Current selected */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            color: #fff !important; /*text-white*/
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06); /*shadow*/
            font-weight: 700; /*font-bold*/
            border-radius: .25rem; /*rounded*/
            background: #667eea !important; /*bg-indigo-500*/
            border: 1px solid transparent; /*border border-transparent*/
        }

        /*Pagination Buttons - Hover */
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            color: #fff !important; /*text-white*/
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06); /*shadow*/
            font-weight: 700; /*font-bold*/
            border-radius: .25rem; /*rounded*/
            background: #667eea !important; /*bg-indigo-500*/
            border: 1px solid transparent; /*border border-transparent*/
        }

        /*Add padding to bottom border */
        table.dataTable.no-footer {
            border-bottom: 1px solid #e2e8f0; /*border-b-1 border-gray-300*/
            margin-top: 0.75em;
            margin-bottom: 0.75em;
        }

        /*Change colour of responsive icon*/
        table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr > th:first-child:before {
            background-color: #667eea !important; /*bg-indigo-500*/
        }

    </style>
@endsection

@section('admin-content')
    <div class="container w-full mx-auto px-2" id="app">
        @include('larapoll::dashboard.partials.header-breadcrumbs')

        <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
            <table v-if="polls.length > 0" id="example" class="stripe hover"
                   style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                <thead>
                <tr>
                    <th data-priority="1">#</th>
                    <th data-priority="2">Question</th>
                    <th data-priority="3">Options</th>
                    <th data-priority="4">Visitors allowed</th>
                    <th data-priority="5">Votes</th>
                    <th data-priority="6">State</th>
                    <th data-priority="7">Edit</th>
                    <th data-priority="8">Delete</th>
                    <th data-priority="9">Lock/Unlock</th>
                </tr>
                </thead>
                <tbody>
                <tr class="text-center" v-for="(poll, index) in polls">
                    <th scope="row">@{{ poll.id }}</th>
                    <td v-html="poll.question"></td>
                    <td>@{{ poll.options_count }}</td>
                    <td>@{{ poll.canVisitorsVote ? 'Yes' : 'No' }}</td>
                    <td>@{{ poll.votes_count }}</td>
                    <td>
                        <span v-if="poll.isLocked" class="label label-danger">Closed</span>
                        <span v-else-if="poll.isComingSoon" class="label label-info">Soon</span>
                        <span v-else-if="poll.isRunning" class="label label-success">Started</span>
                        <span v-else-if="poll.hasEnded" class="label label-success">Ended</span>
                    </td>
                    <td>
                        <a class="btn btn-info btn-sm" :href="poll.edit_link">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-info btn-sm" href="#" @click.prevent="deletePoll(index)">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-info btn-sm" href="#" @click.prevent="toggleLock(index)">
                            <i v-if="poll.isLocked" class="fa fa-unlock" aria-hidden="true"></i>
                            <i v-else class="fa fa-lock" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
            <small v-else>No poll has been found. Try to add one <a href="{{ route('poll.create') }}">Now</a></small>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
    <!--Datatables -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script>
        new Vue({
            el: "#app",
            data() {
                return {
                    polls: {!! json_encode($polls) !!},
                }
            },
            mounted() {
                $('#example').DataTable({
                    responsive: true
                }).columns.adjust().responsive.recalc();
            },
            methods: {
                deletePoll(index) {
                    if (confirm('Do You really want to delete this poll?')) {
                        axios.delete(this.polls[index].delete_link)
                            .then((response) => {
                                this.polls.splice(index, 1);
                            });
                    }
                },
                toggleLock(index) {
                    if (this.polls[index].isLocked) {
                        this.unlock(index);
                        return;
                    }

                    this.lock(index)
                },
                lock(index) {
                    if (confirm('Do You really want to lock this poll?')) {
                        axios.patch(this.polls[index].lock_link)
                            .then((response) => {
                                this.assignNewData(response)
                            });
                    }
                },
                unlock(index) {
                    if (confirm('Do You really want to unlock this poll?')) {
                        axios.patch(this.polls[index].unlock_link)
                            .then((response) => {
                                this.assignNewData(response)
                            });
                    }
                },
                assignNewData(response) {
                    this.polls[index].isLocked = response.data.poll.isLocked;
                    this.polls[index].isRunning = response.data.poll.isRunning;
                    this.polls[index].isComingSoon = response.data.poll.isComingSoon;
                }
            }
        })
    </script>
@endsection
