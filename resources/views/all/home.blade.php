<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>IDS College Queuing System</title>

    <!-- Fullscreen + PWA Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Bootstrap CSS/JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Styles -->
    <style>
        html,
        body {
            height: 100%;
            width: 100%;
            margin: 0;
            overflow: hidden;
            background-color: #a9d18e;
            font-family: 'Segoe UI', sans-serif;
            -webkit-user-select: none;
            user-select: none;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .title-text h4 {
            margin: 0;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .subtitle {
            font-style: italic;
            font-size: 1rem;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .btn-modern {
            background-color: #3e8e41;
            color: white;
            border: none;
            padding: 35px 50px;
            font-size: 2.2rem;
            font-weight: bold;
            min-width: 260px;
            border-radius: 16px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .btn-modern:hover {
            background-color: #2f7033;
            transform: translateY(-3px);
            box-shadow: 0 10px 18px rgba(0, 0, 0, 0.25);
        }

        .check-balance {
            margin-top: 35px;
        }

        @media (max-width: 768px) {
            .btn-modern {
                width: 100%;
                max-width: 95%;
                font-size: 1.8rem;
                padding: 30px;
            }

            .title-text h4 {
                font-size: 1.3rem;
                text-align: center;
            }

            .subtitle {
                font-size: 1rem;
            }

            .logo {
                width: 60px;
            }

            .header {
                flex-direction: column;
            }
        }

        #queue_button {
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 20px;
            right: 20px;
            /* width: 100%; */
        }

        #queue_button button {

            /* distance from bottom */

            /* distance from left */
            padding: 10px 20px;
            font-size: 1rem;
            position: fixed;
            left: 20px;
            color: white;
            bottom: 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <!-- Logo and Title -->
    <div class="header">
        <img src="{{ asset('img/idslogo.png') }}" alt="IDSC Logo" class="logo">
        <div class="title-text">
            <h4>WELCOME TO IDS COLLEGE QUEUEING SYSTEM</h4>
            <div class="subtitle">(A global College in the heart of <a href="#">Albay</a>)</div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="button-group">
        <button class="btn-modern" data-bs-toggle="modal" data-bs-target="#registrarModal">REGISTRAR</button>
        <button class="btn-modern" data-bs-toggle="modal" data-bs-target="#cashierModal">CASHIER</button>
    </div>

    <div class="check-balance">
        <button class="btn-modern" data-bs-toggle="modal" data-bs-target="#balanceModal">CHECK MY BALANCE</button>
    </div>
    <div id="queue_button">
        <a href="{{ route('all.queueing') }}">
            <button class="btn-modern">QUEUEING</button>
        </a>
        <form id="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <input type="submit" value="Logout" />
        </form>

    </div>

    <!-- Modals -->

    <div class="modal fade" id="registrarModal" tabindex="-1" aria-labelledby="registrarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Queue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Content for Registrar Queue
                </div>
            </div>
        </div>
    </div>

    <!-- Include DB connection -->

    <!-- CASHIER Modal -->
    <div class="modal fade" id="cashierModal" tabindex="-1" aria-labelledby="cashierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">CASHIER QUEUE FORM</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <!-- CASHIER Form -->
                    <form method="POST" onsubmit="saveQueue(event); return false;">

                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>

                        <input type="hidden" id="transaction_id" name="transaction_id" class="form-control" value="1">

                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose:</label>
                            <select name="purpose" id="purpose" class="form-control">
                                <option value="Tuition Fee">Tuition Fee</option>
                                <option value="Document Requirements">Document Requirements</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="print_cashier" class="btn btn-primary">Print</button>
                        </div>
                    </form>


                    <!-- PHP Back-end Logic -->
                    <?php
                    if (isset($_POST['print_cashier'])) {
                        include 'admin/db_connect.php';

                        $name = $conn->real_escape_string($_POST['cashier_name']);
                        $transaction_id = 1; // 1 = CASHIER

                        $data = " name = '$name' ";
                        $queue_no = 1001;

                        $totalcount = $conn->query("SELECT * FROM queue_list where date(date_created) = '" . date("Y-m-d") . "' ")->num_rows;
                        $queue_no += $totalcount;
                        $data .= ", transaction_id = '$transaction_id' ";

                        $data .= ", queue_no = '$queue_no' ";

                        $insert = $conn->query("INSERT INTO queue_list set " . $data);
                        if ($insert) {
                            $last_id = $conn->insert_id;
                            echo "<script>
              window.location = 'print.php?id=$last_id';
            </script>";
                        } else {
                            echo '<div class="alert alert-danger mt-3">Error saving queue: ' . $conn->error . '</div>';
                        }
                    }
                    ?>
                    <!-- End PHP Logic -->

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="balanceModal" tabindex="-1" aria-labelledby="balanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Account Balance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="student-id" class="form-control" placeholder="Enter Student ID">
                    <button type="button" class="btn btn-primary mt-3" onclick="checkBalance()">Check Balance</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function saveQueue(e) {
            e.preventDefault();
            const name = document.getElementById('name').value;
            const transactionId = document.getElementById('transaction_id').value;
            const purpose = document.getElementById('purpose').value;
            if (name && transactionId) {
                $.ajax({
                    url: '/all/queueing/save',
                    method: 'POST',
                    data: {
                        name: name,
                        transaction_id: transactionId,
                        purpose: purpose,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 1) {
                            document.getElementById('name').value = "";
                            document.getElementById('purpose').value = "Tuition Fee";
                        } else {
                            alert('Error saving queue: ' + response.message);
                        }
                    },
                    error: function(err) {
                        console.error("AJAX error:", err);
                    }
                });
            } else {
                alert('Please fill in all fields.');
            }
        }
    </script>
    <script type="module" src="/utils.js"></script>

</body>

</html>