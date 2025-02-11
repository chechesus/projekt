<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Vlaky - Adminský panel</title><!--begin::Primary Meta Tags-->
    <link rel="icon" href="../../../images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="AdminLTE v4 | Dashboard">
    <meta name="author" content="ColorlibHQ">
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS.">
    <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"><!--end::Primary Meta Tags--><!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous"><!--end::Fonts--><!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous"><!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous"><!--end::Third Party Plugin(Bootstrap Icons)--><!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="../../adminCSS.css"><!--end::Required Plugin(AdminLTE)--><!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous"><!-- jsvectormap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous">
</head> <!--end::Head--> <!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary"> <!--begin::App Wrapper-->
    <div class="app-wrapper"> <!--begin::Header-->
       
        <aside class="app-sidebar">
            <?php require_once '../sidebar-menu/index.php'; ?>
        </aside>
        <main class="app-main"> <!--begin::App Content Header-->
         
            <div class="app-content"> <!--begin::Container-->
           
                    <div class="row"> <!-- Start col -->
                        <div class="col-lg-7 connectedSortable">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h3 class="card-title">Štatistika prihlásení a registrácí</h3>
                                </div>
                                <div class="card-body">
                                    <div class="revenue-chart"></div>
                                </div>
                            </div> <!-- /.card --> <!-- DIRECT CHAT -->
                            <!--<div class="card mb-4">
                                <div class="card-header">
                                    <h3 class="card-title">Sales Value</h3>
                                </div>
                                <div class="card-body">
                                    <div class="revenue-chart"></div>
                                </div>
                            </div>--> <!-- /.card --> <!-- DIRECT CHAT -->
                            <div class="card direct-chat direct-chat-primary mb-4">
                                <div class="card-header">
                                    <h3 class="card-title">Chat s moderatormi</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="direct-chat-messages">
                                        <!-- Conversation data will be loaded here -->
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <form action="#" method="post">
                                        <div class="input-group">
                                            <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-primary">
                                                    Send
                                                </button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> <!-- /.Start col --> <!-- Start col -->
                        <div class="col-lg-5 connectedSortable">
                            <div class="card text-white bg-primary bg-gradient border-primary mb-4">
                                <div class="card-header border-0">
                                    <h3 class="card-title">Metrika za posledných <!--dni -->dni</h3>
                                    <div class="card-tools"> <button type="button" class="btn btn-primary btn-sm" data-lte-toggle="card-collapse"> <i data-lte-icon="expand" class="bi bi-plus-lg"></i> <i data-lte-icon="collapse" class="bi bi-dash-lg"></i> </button> </div>
                                </div>
                                <div class="card-body">
                                    <div id="world-map" style="height: 220px"></div>
                                </div>
                                <div class="card-footer border-0"> <!--begin::Row-->
                                    <div class="row">
                                        <div class="col-4 text-center">
                                            <div id="sparkline-1" class="text-dark"></div>
                                            <div class="text-white">Visitors</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div id="sparkline-2" class="text-dark"></div>
                                            <div class="text-white">Online</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div id="sparkline-3" class="text-dark"></div>
                                            <div class="text-white">Sales</div>
                                        </div>
                                    </div> <!--end::Row-->
                                </div>
                            </div>
                        </div> <!-- /.Start col -->
                    </div> <!-- /.row (main row) -->
                </div> <!--end::Container-->
            </div> <!--end::App Content-->
        </main> <!--end::App Main--> <!--begin::Footer-->

    </div> <!--end::App Wrapper--> <!--begin::Script--> <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script> <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script> <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script> <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="adminlte.js"></script> <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ=" crossorigin="anonymous"></script> <!-- sortablejs -->
    <script>
        const connectedSortables =
            document.querySelectorAll(".connectedSortable");
        connectedSortables.forEach((connectedSortable) => {
            let sortable = new Sortable(connectedSortable, {
                group: "shared",
                handle: ".card-header",
            });
        });

        const cardHeaders = document.querySelectorAll(
            ".connectedSortable .card-header",
        );
        cardHeaders.forEach((cardHeader) => {
            cardHeader.style.cursor = "move";
        });
    </script> <!-- apexcharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script> <!-- ChartJS -->
    <script>
        function direct_messages() {
            document.addEventListener("DOMContentLoaded", function() {
            const sender_id = <?php echo $_SESSION['user_id']; ?>; // Use actual user ID from session
            const receiver_id = <?php echo $_GET['receiver_id']; ?>; // Get receiver ID from query parameters
            const role_id = <?php echo $_SESSION['role']; ?>;
            const my_user_id = sender_id;

            fetch(`conversation_data.php?sender_id=${sender_id}&receiver_id=${receiver_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }
                    data.forEach(function(value) {
                        // Determine if the message is sent by you or the other person
                        const isMyMessage = (value.sender_id === my_user_id);
                        const messageClass = isMyMessage ? "direct-chat-msg end" : "direct-chat-msg";
                        const messageAlignment = isMyMessage ? "float-end" : "float-start";
                        const timestampAlignment = isMyMessage ? "float-start" : "float-end";
                        const userImage = isMyMessage ? "" : "";
                        const userName = isMyMessage ? "Sarah Bullock" : "Alexander Pierce";

                        var conversation_html = `
                        <div class='${messageClass}'>
                            <div class='direct-chat-infos clearfix'>
                                <span class='direct-chat-name ${messageAlignment}'>${userName}</span>
                                <span class='direct-chat-timestamp ${timestampAlignment}'>${value.timestamp}</span>
                            </div>
                            <img class='direct-chat-img' src='${userImage}' alt='message user image'>
                            <div class='direct-chat-text'>${value.message}</div>
                        </div>`;

                        // Append the conversation HTML to the first "Direct Messages" section
                        document.querySelector(".direct-chat-messages").insertAdjacentHTML('beforeend', conversation_html);
                    });
                })
                .catch(error => console.error('Error fetching conversation data:', error));
        }); 
            };
        // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
        // IT'S ALL JUST JUNK FOR DEMO
        // ++++++++++++++++++++++++++++++++++++++++++

        const sales_chart_options = {
            series: [{
                    name: "Digital Goods",
                    data: [28, 48, 40, 19, 86, 27, 90],
                },
                {
                    name: "Electronics",
                    data: [65, 59, 80, 81, 56, 55, 40],
                },
            ],
            chart: {
                height: 300,
                type: "area",
                toolbar: {
                    show: false,
                },
            },
            legend: {
                show: false,
            },
            colors: ["#0d6efd", "#20c997"],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: "smooth",
            },
            xaxis: {
                type: "datetime",
                categories: [
                    "2023-01-01",
                    "2023-02-01",
                    "2023-03-01",
                    "2023-04-01",
                    "2023-05-01",
                    "2023-06-01",
                    "2023-07-01",
                ],
            },
            tooltip: {
                x: {
                    format: "MMMM yyyy",
                },
            },
        };
        // ++++++++++++++++++++++++++++++++++++++++++
        // Načítaj údaje z PHP
        /*try {
            fetch('../cms/get_salesdata.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Chart Data:", data); // Debugging: Zobrazenie prijatých dát
                    const chart = new ApexCharts(document.querySelector("#chart"), data);
                    chart.render();
                })
                .catch(error => {
                    //console.error("Error loading chart data:", error); // Debugging: Chyby pri načítavaní
                });
        } catch (error) {
            //console.error("Error loading sales chart data:", error);
            header("Location:../cms/get_salesdata.php");
            <?php
            //echo '<script>alert(' . json_encode($sales_chart_options) . ');</script>';?>

        }*/
    </script> <!-- jsvectormap -->
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script> <!-- jsvectormap -->
    <script>
        const visitorsData = {
            US: 398, // USA
            SA: 400, // Saudi Arabia
            CA: 1000, // Canada
            DE: 500, // Germany
            FR: 760, // France
            CN: 300, // China
            AU: 700, // Australia
            BR: 600, // Brazil
            IN: 800, // India
            GB: 320, // Great Britain
            RU: 3000, // Russia
        };

        // World map by jsVectorMap
        const map = new jsVectorMap({
            selector: "#world-map",
            map: "world",
        });

        // Sparkline charts
        const option_sparkline1 = {
            series: [{
                data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
            }, ],
            chart: {
                type: "area",
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: "straight",
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ["#DCE6EC"],
        };

        const sparkline1 = new ApexCharts(
            document.querySelector("#sparkline-1"),
            option_sparkline1,
        );
        sparkline1.render();

        const option_sparkline2 = {
            series: [{
                data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
            }, ],
            chart: {
                type: "area",
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: "straight",
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ["#DCE6EC"],
        };

        const sparkline2 = new ApexCharts(
            document.querySelector("#sparkline-2"),
            option_sparkline2,
        );
        sparkline2.render();

        const option_sparkline3 = {
            series: [{
                data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
            }, ],
            chart: {
                type: "area",
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: "straight",
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ["#DCE6EC"],
        };

        const sparkline3 = new ApexCharts(
            document.querySelector("#sparkline-3"),
            option_sparkline3,
        );
        sparkline3.render();

       
       
    </script> <!--end::Script-->
    <?php
    //header("Location: auth\auth.php");//admin session check
    ob_start(); // Start output buffering
    if (session_status() === PHP_SESSION_NONE) {
        require_once '../stats.php';
    }
    ?>