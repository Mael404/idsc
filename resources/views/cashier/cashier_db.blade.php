@extends('layouts.main')

@section('tab_title', 'Dashboard')
@section('cashier_sidebar')
@include('cashier.cashier_sidebar')
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
                            _token: '{{ csrf_token() }}',
                            transaction_id: 1
                        },
                        success: function(response) {
                            if (response?.status !== 1) {
                                $('#sid').val(null);
                                $("#sname").text("");
                                $("#squeue").html("NO QUEUE");
                                $("#purpose").text("");
                                return;
                            }

                            const data = response.data;
                            $('#sid').val(data.id);
                            $('#sname').text(data.name);
                            $('#squeue').text(data.queue_no);
                            $('#purpose').text(data.purpose);
                        },
                        error: function(xhr) {
                            let err = xhr.responseJSON?.error || "Error getting queue.";
                            alert(err);
                        }
                    });
                } catch (error) {
                    console.error('Error in queueNow:', error);
                }
            }

            function queueRecall() {
                const sid = $('#sid').val();
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
                        } else {
                            alert("Recall failed: " + (response.error || "Unknown error."));
                            console.log("Recall failed:", response);
                        }
                    },
                    error: function(xhr) {
                        const err = xhr.responseJSON?.error || "Error during recall.";
                        alert("Recall failed: " + err);
                        console.error("XHR Error:", err);
                    }
                });
            }
        </script>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 text-center">
                    <button type="button" class="btn btn-primary" onclick="queueNow()">Next Serve</button>
                    <button class="btn btn-primary" onclick="queueRecall()">Recall Again</button>
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

        @include('layouts.footer')
    </div>
</div>
@endsection