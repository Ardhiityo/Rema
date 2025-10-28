 <div class="px-0 container-fluid nav-bar px-lg-4 py-lg-0">
     <div class="container">
         <nav class="py-2 navbar navbar-expand-lg navbar-light">
             <a href="{{ route('landing_page.index') }}" class="p-0 navbar-brand">
                 <h1 class="mb-0 text-primary">
                     <div class="d-flex flex-column">
                         <div class="d-flex flex-column align-items-center">
                             <div class="gap-2 d-flex align-items-center">
                                 <img src="{{ asset('assets/logo/favicon.png') }}" class="img-fluid" alt="unival">
                                 <span class="fw-bold">Rema FIK</span>
                             </div>
                             <small class="fs-6">
                                 <small>Repositori Mahasiswa</small>
                             </small>
                         </div>
                     </div>
                 </h1>
             </a>
             <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                 <span class="fa fa-bars"></span>
             </button>
             <div class="collapse navbar-collapse" id="navbarCollapse">
                 <div class="navbar-nav">
                     <a href="#home" class="nav-item nav-link">Home</a>
                     <a href="#repositories" class="nav-item nav-link">Repositories</a>
                     <a href="#contact" class="nav-item nav-link">Contact</a>
                 </div>
             </div>
             <div class="flex-shrink-0 d-none d-xl-flex ps-4">
                 <a href="tel:628989649370" target="_blank"
                     class="btn btn-light btn-lg-square rounded-circle position-relative wow tada" data-wow-delay=".9s">
                     <i class="fa fa-phone-alt fa-2x"></i>
                     <div class="position-absolute" style="top: 7px; right: 12px;">
                         <span><i class="fa fa-comment-dots text-secondary"></i></span>
                     </div>
                 </a>
                 <div class="d-flex flex-column ms-3">
                     <span>More Information</span>
                     <a href="tel:628989649370" target="_blank"><span class="text-dark">Free: +62 898 964
                             9370</span></a>
                 </div>
             </div>
         </nav>
     </div>
 </div>
