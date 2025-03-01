<?php
require_once '/xampp/htdocs/projekt/api/session.php';
require_once 'auth/auth.php';
require_once 'C:\xampp\htdocs\projekt\stats.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Vlaky - Adminský panel</title>
  <link rel="icon" href="../images/logo.jpg">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Fonts & Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
  <!-- OverlayScrollbars -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="/projekt/style.css">
  <!-- ApexCharts CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" crossorigin="anonymous">
  <!-- jsVectorMap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" crossorigin="anonymous">
  <!-- Additional fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous">

  <style>
    /* Možno si uprav štýly podľa potreby */
    #revenue-chart {
      height: 300px;
    }

    .direct-chat-msg.end {
      text-align: right;
    }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    <!-- Navigation -->
    <?php require_once 'includes/nav.php'; ?>
    <!-- Sidebar -->
    <aside class="app-sidebar">
      <aside class="sidebar">
        <?php require_once '../cms/sidebar-menu/index.php'; ?>
      </aside>
    </aside>
    <!-- Main content -->
    <main class="app-main">
      <div class="app-content">
        <div class="container-fluid">
          <!-- Statistika widgety -->
          <div class="row">
            <!-- Prihlásení dnes -->
            <div class="col-lg-3 col-6">
              <div class="small-box text-bg-primary">
                <div class="inner">
                  <h3><?php loged() ?></h3>
                  <p>Prihlásených dnes</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"></path>
                </svg>
              </div>
            </div>
            <!-- Registrovaní dnes -->
            <div class="col-lg-3 col-6">
              <div class="small-box text-bg-success">
                <div class="inner">
                  <h3><?php registrated() ?><sup class="fs-5"></sup></h3>
                  <p>Registrovaných dnes</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122z"></path>
                </svg>
              </div>
            </div>
            <!-- RAM záťaž -->
            <div class="col-lg-3 col-6">
              <div class="small-box text-bg-warning">
                <div class="inner">
                  <h3><?= getRamUsagePercentage() . '%' ?></h3>
                  <p>RAM záťaž</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
                </svg>
              </div>
            </div>
            <!-- CPU záťaž -->
            <div class="col-lg-3 col-6">
              <div class="small-box text-bg-danger">
                <div class="inner">
                  <h3><?= getCpuLoadPercentage() . '%' ?></h3>
                  <p>CPU záťaž</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                  <path clip-rule="evenodd" fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z"></path>
                  <path clip-rule="evenodd" fill-rule="evenodd" d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z"></path>
                </svg>
              </div>
            </div>
          </div>
          <!-- Row so štatistikou a mapou -->
          <div class="row">
            <!-- Chart karta -->
            <div class="col-lg-7 connectedSortable">
              <div class="card mb-4">
                <div class="card-header">
                  <h3 class="card-title">Štatistika - Registrácie vs. Prihlásenia</h3>
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <select id="timeRange" class="form-control">
                        <option value="today">Dnes</option>
                        <option value="week">Tento týždeň</option>
                        <option value="year">Tento rok</option>
                        <option value="all">Od počiatku</option>
                        <option value="custom">Vlastné obdobie</option>
                      </select>
                    </div>
                    <div class="col-md-6" id="customRangeContainer" style="display: none;">
                      <div class="input-group">
                        <input type="date" id="startDate" class="form-control">
                        <input type="date" id="endDate" class="form-control">
                        <button class="btn btn-primary" onclick="fetchChartData()">Zobraziť</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div id="revenue-chart"></div>
                </div>
              </div>
              <!-- Chat karta -->
              <div class="card direct-chat direct-chat-primary mb-4">
                <div class="card-header">
                  <h3 class="card-title">Chat s moderátormi</h3>
                  <div class="card-tools">
                    <span id="messageCount" class="badge text-bg-primary">0</span>
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                      <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                      <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                    </button>
                    <button type="button" class="btn btn-tool" title="Contacts" data-lte-toggle="chat-pane">
                      <i class="bi bi-chat-text-fill"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                      <i class="bi bi-x-lg"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="direct-chat-messages" id="chatbox">
                    <!-- Dynamicky načítané správy -->
                  </div>
                  <div class="direct-chat-contacts">
                    <ul class="contacts-list" id="contactsList">
                      <!-- Dynamicky načítané kontakty -->
                    </ul>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="input-group">
                    <input type="text" id="message" placeholder="Napíš správu..." class="form-control">
                    <span class="input-group-text">
                      <button onclick="sendMessages()" class="btn btn-primary">Odoslať</button>
                    </span>
                  </div>
                </div>
              </div>

              <div class="card mb-4">
              <div class="card-header">
                <h3 class="card-title">Štatistika pripojených používateľov</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div id="pie-chart"></div>
                  </div>
                </div>
              </div>
            </div>
            </div>
          </div>
      </div>
    </main>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const chartElement = document.querySelector("#pie-chart");
      if (chartElement) {
        // Vytvorenie grafu
        const pie_chart = new ApexCharts(chartElement, pie_chart_options);
        pie_chart.render();
      } else {
        console.error("Element with ID 'pie-chart' was not found in the DOM.");
      }
    });

    // Funkcia na získanie a spracovanie dát z get_chart_data.php
    async function fetchChartData() {
      const timeRange = document.getElementById("timeRange");
      const startDate = document.getElementById("startDate")?.value;
      const endDate = document.getElementById("endDate")?.value;
      const range = timeRange.value;

      console.log("Requesting data with range:", range, "startDate:", startDate, "endDate:", endDate);

      try {
        const response = await fetch(`admin_funct/get_chart_data.php?range=${range}&start=${startDate}&end=${endDate}`);
        if (!response.ok) {
          throw new Error(`Server responded with status ${response.status}`);
        }
        const data = await response.json();

        // Vypíšeme prijaté údaje do konzoly
        console.log("Fetched chart data:", data);

        // Skontrolujeme, či dáta obsahujú očakávané polia
        if (data && Array.isArray(data.categories) && Array.isArray(data.registered) && Array.isArray(data.logged)) {
          // Aktualizujeme graf (predpokladáme, že sales_chart už je inicializovaný)
          sales_chart.updateOptions({
            xaxis: {
              categories: data.categories
            }
          });
          sales_chart.updateSeries([{
              name: "Registrovaní",
              data: data.registered
            },
            {
              name: "Prihlásení",
              data: data.logged
            }
          ]);
        } else {
          console.warn("Received data does not contain the expected format or fields.");
        }
      } catch (err) {
        console.error("Error fetching chart data:", err);
      }
    }

    // Nastavíme event listener na zmenu výberu
    document.getElementById("timeRange").addEventListener("change", function() {
      console.log("Selection changed to:", this.value);
      fetchChartData();
    });

    // Pri prvom načítaní stránky tiež spustíme fetchChartData()
    document.addEventListener("DOMContentLoaded", fetchChartData);
  </script>

  <!-- Global wsConfig -->
  <script>
    window.wsConfig = {
      userId: <?= json_encode($userId); ?>,
      roleId: <?= json_encode($roleId); ?>,
      csrfToken: <?= json_encode($_SESSION['csrf_token'] ?? ''); ?>
    };
  </script>
  <script>
    const timeRange = document.getElementById('timeRange');
    timeRange.addEventListener('change', function() {
      console.log('Selected time range:', this.value);
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      console.log('Default selected time range:', timeRange.value);
    });
  </script>


  <!-- Externé knižnice -->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
  <!-- Vlastné skripty -->
  <script type="module" src="/projekt/scripts.js"></script>
  <script>
    // Chat functionality
    const wsUrl = "ws://localhost:50000?user_id=" + wsConfig.userId + "&role_id=" + wsConfig.roleId;
    const ws = new WebSocket(wsUrl);
    ws.onopen = function() {
      console.log("WebSocket pripojené.");
      ws.send(JSON.stringify({
        action: "getCounts",
        user_id: wsConfig.userId,
        role_id: wsConfig.roleId
      }));
    };
    ws.onmessage = function(event) {
      try {
        const data = JSON.parse(event.data);
        if (data.action === "getCounts") {
          document.getElementById("notificationBadge").textContent = data.notificationCount;
          document.getElementById("notifCountText").textContent = data.notificationCount;
          document.getElementById("messageBadge").textContent = data.messageCount;
        }
        if (data.status === "blocked") {
          alert("Ste zablokovaný: " + data.reason);
          setTimeout(() => window.location.href = "../api/logout.php", 2000);
        }
      } catch (e) {
        console.error("Chyba pri parsovaní WS správy:", e);
      }
    };
    ws.onerror = function(error) {
      console.error("WS error:", error);
    };
    ws.onclose = function() {
      console.log("WebSocket spojenie zatvorené.");
    };

    // Chat messages and contacts
    async function loadContacts() {
      try {
        const response = await fetch("admin_funct/fetch_contacts.php");
        const data = await response.json();
        const contactsContainer = document.getElementById("contactsList");
        contactsContainer.innerHTML = "";
        data.forEach(contact => {
          const li = document.createElement("li");
          li.innerHTML = `
            <a href="#" class="contact-item" data-receiver-id="${contact.id}" data-receiver-role="${contact.role_id}">
              <img class="contacts-list-img" src="${contact.profile_picture || '/projekt/images/user_ico.png'}" alt="User Avatar">
              <div class="contacts-list-info">
                <span class="contacts-list-name">${contact.name} <small class="contacts-list-date float-end">${contact.last_message_timestamp ? new Date(contact.last_message_timestamp).toLocaleDateString() : ""}</small></span>
                <span class="contacts-list-msg">${contact.last_message ? contact.last_message.substring(0,30) + (contact.last_message.length > 30 ? "..." : "") : "Start a conversation"}</span>
              </div>
            </a>
          `;
          contactsContainer.appendChild(li);
          li.querySelector(".contact-item").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("receiver_id").value = this.dataset.receiverId;
            document.getElementById("receiver_role").value = this.dataset.receiverRole;
            localStorage.setItem("receiver_id", this.dataset.receiverId);
            localStorage.setItem("receiver_role", this.dataset.receiverRole);
            document.querySelector(".direct-chat-contacts").classList.add("d-none");
            loadMessages();
          });
        });
      } catch (error) {
        console.error("Error loading contacts:", error);
      }
    }
    async function loadMessages() {
      const receiverId = document.getElementById("receiver_id").value;
      const receiverRole = document.getElementById("receiver_role").value;
      if (!receiverId) {
        console.error("Receiver ID is not set!");
        return;
      }
      try {
        const response = await fetch(`admin_funct/get_msg.php?receiver_id=${receiverId}&receiver_role=${receiverRole}`);
        const data = await response.json();
        const chatbox = document.getElementById("chatbox");
        chatbox.innerHTML = "";
        data.forEach(msg => {
          const div = document.createElement("div");
          div.className = (msg.sender_id == wsConfig.userId && msg.sender_role == wsConfig.roleId) ? "direct-chat-msg end" : "direct-chat-msg";
          div.innerHTML = `
            <div class="direct-chat-infos clearfix">
              <span class="direct-chat-name float-${msg.sender_id == wsConfig.userId ? "end" : "start"}">
                ${msg.sender_id == wsConfig.userId ? "Ja" : msg.sender_name || "Odosielateľ"}
              </span>
              <span class="direct-chat-timestamp float-${msg.sender_id == wsConfig.userId ? "start" : "end"}">
                ${msg.timestamp ? new Date(msg.timestamp).toLocaleString() : ""}
              </span>
            </div>
            <img class="direct-chat-img" src="${msg.sender_id == wsConfig.userId ? "../images/user_ico.png" : "../images/receiver_ico.png"}" alt="User Image">
            <div class="direct-chat-text">${msg.message}</div>
          `;
          chatbox.appendChild(div);
        });
        chatbox.scrollTop = chatbox.scrollHeight;
        document.getElementById("messageCount").textContent = data.length;
      } catch (error) {
        console.error("Error loading messages:", error);
      }
    }
    async function sendMessages() {
      const receiverId = document.getElementById("receiver_id")?.value;
      const receiverRole = document.getElementById("receiver_role")?.value;
      const messageInput = document.getElementById("message");
      const message = messageInput?.value.trim();
      if (!receiverId || !receiverRole || !message) {
        alert("Chýbajúce údaje!");
        return;
      }
      try {
        const response = await fetch("admin_funct/send_msg.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: `receiver_id=${receiverId}&receiver_role=${receiverRole}&message=${encodeURIComponent(message)}&csrf_token=${wsConfig.csrfToken}`
        });
        const data = await response.json();
        if (data.status === "success") {
          messageInput.value = "";
          loadMessages();
        } else {
          alert("Chyba pri odosielaní správy.");
        }
      } catch (error) {
        console.error("Error sending message:", error);
      }
    }
    window.sendMessages = sendMessages;

    // Inicializácia Sortable pre connectedSortable kontajnery
    document.querySelectorAll(".connectedSortable").forEach(el => {
      new Sortable(el, {
        group: "shared",
        handle: ".card-header"
      });
    });
    document.querySelectorAll(".connectedSortable .card-header").forEach(header => {
      header.style.cursor = "move";
    });

    // Načítanie kontaktov a správy po načítaní stránky
    document.addEventListener("DOMContentLoaded", () => {
      loadContacts();
      // Ak existujú uložené údaje o poslednom chate, obnovíme ich
      const savedReceiverId = localStorage.getItem("receiver_id");
      const savedReceiverRole = localStorage.getItem("receiver_role");
      if (savedReceiverId && savedReceiverRole) {
        document.getElementById("receiver_id").value = savedReceiverId;
        document.getElementById("receiver_role").value = savedReceiverRole;
        loadMessages();
      }
    });
  </script>
  <script>
    const pie_chart_options = {
      series: [],
      chart: {
        type: "donut",
      },
      labels: [],
      dataLabels: {
        enabled: false,
      },
      colors: [
        "#0d6efd",
        "#20c997",
        "#ffc107",
        "#d63384",
        "#6f42c1",
        "#adb5bd",
      ],
    };

    document.addEventListener("DOMContentLoaded", function() {
      fetch("admin_funct/get_country_stats.php")
        .then(response => response.json())
        .then(data => {
          pie_chart_options.labels = data.labels;
          pie_chart_options.series = data.series;

          const pie_chart = new ApexCharts(
            document.querySelector("#pie-chart"),
            pie_chart_options
          );
          pie_chart.render();
        })
        .catch(err => {
          console.error("Chyba pri načítaní údajov pre graf:", err);
        });
    });
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Pie chart initialization
      const pieChartOptions = {
        series: [],
        chart: {
          type: "donut",
        },
        labels: [],
        dataLabels: {
          enabled: false,
        },
        colors: ["#0d6efd", "#20c997", "#ffc107", "#d63384", "#6f42c1", "#adb5bd"],
      };

      const pieChartElement = document.querySelector("#pie-chart");
      if (pieChartElement) {
        fetch("admin_funct/get_country_stats.php")
          .then((response) => response.json())
          .then((data) => {
            pieChartOptions.labels = data.labels;
            pieChartOptions.series = data.series;
            const pieChart = new ApexCharts(pieChartElement, pieChartOptions);
            pieChart.render();
          })
          .catch((error) => {
            console.error("Error fetching data for pie chart:", error);
          });
      }

      // Line chart initialization
      const salesChartOptions = {
        series: [{
            name: "Registered",
            data: [],
          },
          {
            name: "Logged in",
            data: [],
          },
        ],
        chart: {
          height: 300,
          type: "area",
          toolbar: {
            show: false,
          },
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
          categories: [],
        },
        tooltip: {
          x: {
            format: "dd.MM.yyyy",
          },
        },
      };

      let salesChart = null;

      if (typeof ApexCharts !== "undefined") {
        const salesChartElement = document.querySelector("#revenue-chart");
        if (salesChartElement) {
          salesChart = new ApexCharts(salesChartElement, salesChartOptions);
          salesChart.render();
        }
      }

      // Fetching chart data
      async function fetchChartData() {
        const timeRange = document.getElementById("timeRange");
        const startDate = document.getElementById("startDate")?.value;
        const endDate = document.getElementById("endDate")?.value;
        const range = timeRange ? timeRange.value : "all";

        try {
          const response = await fetch(
            `admin_funct/get_chart_data.php?range=${range}&start=${startDate}&end=${endDate}`
          );
          if (!response.ok) {
            throw new Error(`Server responded with status ${response.status}`);
          }

          const data = await response.json();
          if (
            data &&
            Array.isArray(data.categories) &&
            Array.isArray(data.registered) &&
            Array.isArray(data.logged)
          ) {
            salesChart.updateOptions({
              xaxis: {
                categories: data.categories,
              },
            });
            salesChart.updateSeries([{
                name: "Registered",
                data: data.registered,
              },
              {
                name: "Logged in",
                data: data.logged,
              },
            ]);
          }
        } catch (error) {
          console.error("Error fetching chart data:", error);
        }
      }

      if (document.getElementById("timeRange")) {
        document
          .getElementById("timeRange")
          .addEventListener("change", fetchChartData);
      }

      // Call fetchChartData on page load
      fetchChartData();
    });
    let sales_chart; // Definícia globálnej premennej

    function renderPieChart(chartData) {
      const pieChartEl = document.getElementById("pie-chart");
      if (!pieChartEl) {
        console.error("Element with ID 'pie-chart' not found in the DOM.");
        return;
      }
      sales_chart = new ApexCharts(pieChartEl, {
        chart: {
          type: 'pie'
        },
        series: chartData.series, // Napríklad [44, 55, 13, 43, 22]
        labels: chartData.labels // Napríklad ['A', 'B', 'C', 'D', 'E']
      });
      sales_chart.render();
    }

    async function fetchChartData() {
      try {
        const response = await fetch("./admin_funct/mapdata.php?range=today");
        const data = await response.json();
        if (data && data.series && data.labels) {
          renderPieChart(data);
        } else {
          console.error("Invalid chart data received:", data);
        }
      } catch (error) {
        console.error("Error fetching chart data:", error);
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
      // Uistite sa, že element s ID 'pie-chart' existuje v HTML
      fetchChartData();
    });
  </script>


  <!-- Hidden inputs pre chat -->
  <input type="hidden" id="receiver_id" value="">
  <input type="hidden" id="receiver_role" value="">

</body>

</html>