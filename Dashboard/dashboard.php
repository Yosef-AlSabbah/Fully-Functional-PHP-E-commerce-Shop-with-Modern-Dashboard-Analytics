<?php
include_once "include/header.php";
getHeader("Home");
require_once "../config/DB_Connection.php";
$numOfStores = DB_Connection::getTotalNumberStores();
$numOfCategories = DB_Connection::getTotalNumberCategories();
?>
<script src="../js/Chart.js/chart.min.js"></script>
<style>
    .card-title {
        text-align: center
    }

    .main-panel {
        margin-top: 1%
    }

    .fas.fa-arrow-down,
    .h1.m-0.mb-3.mt-3 {
        font-size: 16pt
    }

    .fas.fa-arrow-down,
    .fas.fa-arrow-up {
        margin-left: 10px
    }

    .top-line {
        border-top: 1px solid #ebecec !important
    }

    .cust {
        width: 100%;
        margin: auto
    }
</style>
<div class="main-panel">
    <div class="card-body">
        <div class="content py-5 row" style="padding-bottom: 1rem!important;">
            <div class="col-sm-6 col">
                <div class="card card-stats card-primary card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-list-alt"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">Categories</p>
                                    <h4 class="card-title"><?= htmlspecialchars($numOfCategories) ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col">
                <div class="card card-stats card-info card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-store"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">Stores</p>
                                    <h4 class="card-title"><?= htmlspecialchars($numOfStores) ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $interval = "1 MONTH";
        $maxNumOfResults = 6;
        $result = DB_Connection::getStoreRatingTrending($interval, $maxNumOfResults);
        if ($result->num_rows > 0) {
        ?>
            <div class="top-line">
                <h1 class="h1 mt-3 mb-4">Stores Rating Trend</h1>
                <div class="row cust">
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <div class="col-6 col-sm-4 col-lg-2">
                            <div class="card">
                                <div class="card-body p-3 text-center">
                                    <div><a href="../Public/storeInfo?store_id=<?= htmlspecialchars($row['Store ID']) ?>" target="_blank"></a><?= htmlspecialchars($row['Store Name']) ?></div>
                                    <?php if ($row['Store stats'] < 0) { ?>
                                        <div class="h1 m-0 text-danger mb-3 mt-3"><?= htmlspecialchars($row['Store stats']) ?><i class="fas fa-arrow-down"></i>
                                        <?php } else { ?>
                                            <div class="h1 m-0 text-success mb-3 mt-3"><?= htmlspecialchars($row['Store stats']) ?><i class="fas fa-arrow-up"></i>
                                            <?php } ?>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                </div>
            <?php }
            ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Top Ranked Stores Last Month</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="pieChart" style="width: 50%; height: 50%"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Visitors Last Week</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id='myChart'></canvas>
                            </div>
                        </div>
                    </div>
                    <?php


                    $fromInterval = "3 WEEK";
                    $toInterval = "2 WEEK";
                    $result = DB_Connection::getVsistsStats($fromInterval, $toInterval);

                    // Extract the data into arrays
                    $visitDates = array();
                    $visits = array();
                    while ($row = $result->fetch_assoc()) {
                        $visitDates[] = Date("D", strtotime($row['date']));
                        $visits[] = $row['visits'];
                    }
                    echo($visitDates, $visits)
                    echo "<script>
    var myChart = new Chart(document.getElementById('myChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: ['" . implode("', '", $visitDates) . "'],
            datasets: [{
                label: 'Visits',
                data: ['" . implode("', '", $visits) . "'],
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            legend: {
                display: false
            },
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    ticks: {
                        callback: function(value, index, values) {
                            return value.slice(0, 3);
                        }
                    }
                }]
            },
            tooltips: {
                enabled: true,
                mode: 'index',
                intersect: false,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var label = data.datasets[tooltipItem.datasetIndex].label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += tooltipItem.yLabel;
                        return label;
                    }
                }
            }
        }
    });
</script>";



                    $dateInterval = "1 MONTH";
                    $maxNumOfResults = 3;
                    $result = DB_Connection::getTopRatings($dateInterval, $maxNumOfResults);
                    $store_names = array();
                    $raitings = array();
                    while ($row = $result->fetch_assoc()) {
                        $store_names[] = $row['Store Name'];
                        $raitings[] = $row['Rates'];
                    }

                    $labelNames = implode("', '", $store_names);
                    $ratingValues = implode("', '", $raitings);

                    echo <<<"Here"
<script>
    let myPieChart = new Chart(document.getElementById('pieChart').getContext('2d'), {
        type: 'pie',
        data: {
            datasets: [{
                data: ['$ratingValues'],
                backgroundColor: ["#1d7af3", "#f3545d", "#fdaf4b"],
                borderWidth: 0
            }],
            labels: ['$labelNames']
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom',
                labels: {
                    fontColor: 'rgb(154, 154, 154)',
                    fontSize: 11,
                    usePointStyle: true,
                    padding: 20
                }
            },
            pieceLabel: {
                render: 'percentage',
                fontColor: 'white',
                fontSize: 14,
            },
            tooltips: false,
            layout: {
                padding: {
                    left: 20,
                    right: 20,
                    top: 20,
                    bottom: 20
                }
            }
        }
    })
</script>
Here;
                    ?>
                </div>
            </div>
            </div>

            <?php
            include_once "include/footer.php";
            ?>
            <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>
            <script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

            <?php
            if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['success'])) {
                echo "<script>alert('success','Logged In Successfully!', 'Welcome back Mr. {$_GET['success']}');</script>";
            }
            ?>
            </body>

            </html>