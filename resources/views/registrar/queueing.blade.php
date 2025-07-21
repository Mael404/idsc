@extends('layouts.main')

@section('tab_title', 'Dashboard')
@section('registrar_sidebar')
    @include('registrar.registrar_sidebar')
@endsection

@section('content')

<div id="content-wrapper" class="d-flex flex-column">


    <div id="content">

        @include('layouts.topbar')


        <script>
            function queueNow() {
                try {
                    $.ajax({
                        url: "{{ route('cashier.queue.update') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            const data = response?.data;
                            if (data?.status !== 1) {
                                $('#sid').val(null);
                                $("#sname").text("");
                                $("#squeue").html("NO QUERY");
                                $("#purpose").text("");
                                console.log("No query available at the moment.");
                                return;
                            }
                            $('#sid').val(data?.id);
                            $('#sname').text(data?.name);
                            $('#squeue').text(data?.queue_no);
                            $('#purpose').text(data?.purpose);

                        },
                        error: function(xhr) {
                            console.log(xhr)
                            let err = xhr.responseJSON?.error || "No queue available or error occurred.";
                            alert(err);
                        }
                    });
                } catch (error) {
                    console.error('Error in queueNow:', error);
                }
            }

            function queueRecall(btn) {
                const sid = $('#sid').val();
                console.log("Recalling queue with ID:", sid);
                if (!sid) {
                    alert("No current queue selected to recall.");
                    return;
                }

                $.ajax({
                    url: "/cashier/queue/recall/" + sid,
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 1) {
                            const data = response.data;
                            $('#sname').text(data.name);
                            $('#squeue').text(data.queue_no);
                            $('#purpose').text(data.purpose);
                            console.log("Queue recalled:", response);

                        } else {
                            alert("Recall failed.", response);
                            console.error("Recall failed:", response);
                        }
                    },
                    error: function(xhr) {
                        let err = xhr.responseJSON?.error || "Error during recall.";
                        alert(err);
                    }
                });
            }
        </script>
        <div class="container-fluid">

            <!-- Page Heading -->
            <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Cashier Dashboard</h1>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Download Report</a>
                </div> -->

            <!-- Content Row -->
            <div class="row">
                <div class="col-md-4 text-center">
                    <button type="button" class="btn btn-primary" onclick="queueNow()">Next Serve</button>
                    <button class="btn btn-primary" id="recallServeBtn" onclick="queueRecall(this)">Recall Again</button>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="text-center"><b>Now Serving</b></h3>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="sid">
                            <h4 class="text-center" id="sname"></h4>
                            <h3 class="text-center" id="squeue"></h3>
                            <h5 class="text-center" id="purpose"></h5>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Content -->

        <!-- End Page Content -->


    </div>
    <!-- End of Main Content -->

    @include('layouts.footer')

</div>
<!-- End of Content Wrapper -->
@endsection