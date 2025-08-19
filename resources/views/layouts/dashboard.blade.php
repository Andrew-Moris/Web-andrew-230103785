<!DOCTYPE html> 
 <html lang="en" dir="ltr"> 
 <head> 
     <meta charset="UTF-8"> 
     <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
     <title>Admin Dashboard</title> 
     <!-- Google Fonts - Roboto --> 
     <link href=" https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap " rel="stylesheet"> 
     <!-- Bootstrap CSS --> 
     <link href=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css " rel="stylesheet"> 
     <!-- Font Awesome --> 
     <link href=" https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css " rel="stylesheet"> 
     <script src=" https://cdn.jsdelivr.net/npm/chart.js "></script> 
     <style> 
         body { 
             font-family: 'Roboto', sans-serif; 
             background-color: #f8f9fa; /* Light background */ 
             padding-top: 70px; /* Adjust for fixed navbar */ 
         } 
         .card { 
             border-radius: 0.75rem; 
             transition: transform 0.2s, box-shadow 0.2s; 
         } 
         .card:hover { 
             transform: translateY(-5px); 
             box-shadow: 0 10px 20px rgba(0,0,0,0.1); 
         } 
         .chart-container { 
             height: 300px; 
         } 
     </style> 
 </head> 
 <body> 
     <!-- Navbar --> 
     <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top"> 
         <div class="container"> 
             <a class="navbar-brand" href="/"> 
                 Admin Dashboard 
             </a> 
             <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> 
                 <span class="navbar-toggler-icon"></span> 
             </button> 
             <div class="collapse navbar-collapse" id="navbarSupportedContent"> 
                 <ul class="navbar-nav me-auto"> 
                     <li class="nav-item"> 
                         <a class="nav-link" href="/"><i class="fas fa-tachometer-alt"></i> Home</a> 
                     </li> 
                     <li class="nav-item"> 
                         <a class="nav-link" href="#"><i class="fas fa-box"></i> Products</a> 
                     </li> 
                     <li class="nav-item dropdown"> 
                         <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTools" role="button" data-bs-toggle="dropdown" aria-expanded="false"> 
                             <i class="fas fa-tools"></i> Tools 
                         </a> 
                         <ul class="dropdown-menu" aria-labelledby="navbarDropdownTools"> 
                             <li><a class="dropdown-item" href="#">Even Numbers</a></li> 
                             <li><a class="dropdown-item" href="#">Multiplication</a></li> 
                         </ul> 
                     </li> 
                     <li class="nav-item">
                         <a class="nav-link" href="#"><i class="fas fa-book"></i> Catalog</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link" href="/users"><i class="fas fa-users"></i> User Management</a>
                     </li> 
                 </ul> 
                 <ul class="navbar-nav ms-auto"> 
                     <li class="nav-item"> 
                         <a class="nav-link" href="#">Logout</a> 
                     </li> 
                 </ul> 
             </div> 
         </div> 
     </nav> 
      
     <!-- Main Content Area --> 
     <main> 
         <div class="container-fluid"> 
             <div class="container mt-4"> 
                 <div class="row"> 
                     <div class="col-12"> 
                         <div class="d-flex justify-content-between align-items-center mb-4"> 
                             <h1 class="h3 mb-0 text-gray-800">Dashboard</h1> 
                             <div class="d-flex align-items-center"> 
                                 <span id="userIdDisplay" class="text-sm bg-light p-2 rounded me-2">User ID: Loading...</span> 
                                 <button id="simulateUpdateBtn" class="btn btn-primary me-2">Simulate Data Update</button> 
                                 <button class="btn btn-info">Export</button> 
                             </div> 
                         </div> 
          
                         <!-- Stats cards --> 
                         <div class="row g-4 mb-4"> 
                             <div class="col-md-6 col-lg-3"> 
                                 <div class="card h-100"> 
                                     <div class="card-body"> 
                                         <div class="row no-gutters align-items-center"> 
                                             <div class="col me-2"> 
                                                 <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div> 
                                                 <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalUsers"> 
                                                     <i class="fas fa-spinner fa-spin text-primary"></i> 
                                                 </div> 
                                             </div> 
                                             <div class="col-auto"> 
                                                 <i class="fas fa-users fa-2x text-gray-300"></i> 
                                             </div> 
                                         </div> 
                                     </div> 
                                 </div> 
                             </div> 
                             <div class="col-md-6 col-lg-3"> 
                                 <div class="card h-100"> 
                                     <div class="card-body"> 
                                         <div class="row no-gutters align-items-center"> 
                                             <div class="col me-2"> 
                                                 <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Products</div> 
                                                 <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalProducts"> 
                                                     <i class="fas fa-spinner fa-spin text-success"></i>
