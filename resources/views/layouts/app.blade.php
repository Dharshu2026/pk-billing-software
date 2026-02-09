<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PK Agency - Billing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f4f7f6; margin: 0; overflow-x: hidden; }
        
        /* Sidebar Smooth Transition */
        #sidebar-wrapper {
            min-width: 260px;
            max-width: 260px;
            background: #1a252f;
            transition: margin .3s ease-out; /* Smooth effect inga dhaan irukku */
        }

        /* Sidebar toggled state: Margin-ai ulla anupuvom */
        #wrapper.toggled #sidebar-wrapper {
            margin-left: -260px; 
        }

        #sidebar-wrapper .list-group-item:hover {
            background-color: #0056b3 !important;
            transition: 0.3s;
        }

        #billSubmenu a:hover {
            color: #fff9c4 !important;
        }

        .dropdown-toggle::after {
            float: right;
            margin-top: 10px;
        }

        #wrapper { display: flex; width: 100vw; height: 100vh; }

        /* Nav links logic */
        #sidebar-wrapper .nav-link { 
            padding: 15px 25px; 
            font-size: 1.05em; 
            color: #bdc3c7; 
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        #sidebar-wrapper .nav-link:hover { 
            color: #fff; 
            background: #2c3e50; 
            padding-left: 30px;
        }

        #page-content-wrapper { flex: 1; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div class="bg-dark text-white" id="sidebar-wrapper">
            <div class="sidebar-heading p-4 fs-4 fw-bold border-bottom border-secondary text-center">NEW P K AGENCY</div>
            <div class="list-group list-group-flush">
                <a href="/dashboard" class="list-group-item list-group-item-action bg-dark text-white border-0 p-3">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>

                <div class="dropdown">
                    <button class="btn btn-dark w-100 text-start dropdown-toggle p-3 border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#billSubmenu">
                        <i class="bi bi-receipt me-2"></i> Bill Management
                    </button>
                    <div class="collapse ps-4 bg-secondary bg-opacity-10" id="billSubmenu">
                        <a href="{{ route('invoices.create') }}" class="d-block p-2 text-white text-decoration-none small">
                            <i class="bi bi-plus-circle me-2"></i> Add Bill
                        </a>
                        <a href="{{ route('invoices.index') }}" class="d-block p-2 text-white text-decoration-none small">
                            <i class="bi bi-clock-history me-2"></i> Bill History
                        </a>
                        <a href="/billing/modify" class="d-block p-2 text-white text-decoration-none small">
                            <i class="bi bi-pencil-square me-2"></i> Modify Bill
                        </a>
                    </div>
                </div>

                <a href="/inventory" class="list-group-item list-group-item-action bg-dark text-white border-0 p-3">
                    <i class="bi bi-box-seam me-2"></i> Items
                </a>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="p-3 mt-auto">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100 fw-bold shadow-sm">
                    <i class="bi bi-box-arrow-right me-2"></i> LOGOUT
                </button>
            </form>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom p-2">
                <div class="container-fluid">
                    <button class="btn btn-link text-dark shadow-none p-0 me-3" id="menu-toggle">
                        <i class="bi bi-list fs-3"></i> 
                    </button>
                    <span class="fw-bold text-secondary small">PK AGENCY MANAGEMENT</span>
                </div>
            </nav>

            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuToggle = document.getElementById("menu-toggle");
            const wrapper = document.getElementById("wrapper");

            menuToggle.addEventListener("click", function(e) {
                e.preventDefault();
                // Sidebar-ai maraikka toggled class add/remove panrom
                wrapper.classList.toggle("toggled");
            });
        });
    </script>
</body>
</html>