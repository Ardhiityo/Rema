<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>LifeSure - Life Insurance Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link rel="stylesheet" href="{{ asset('lib/animate/animate.min.css') }}" />
    <link href="{{ asset('lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>
    <x-spinner />
    <x-navbar />

    {{ $slot }}

    <!-- Footer Start -->
    <div class="py-5 container-fluid footer wow fadeIn" data-wow-delay="0.2s" id="contact">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-xl-9">
                    <div class="mb-5">
                        <div class="row g-4">
                            <div class="col-md-6 col-lg-6 col-xl-5">
                                <div class="footer-item">
                                    <a href="index.html" class="p-0">
                                        <h3 class="text-white"><i class="fab fa-slack me-3"></i> LifeSure</h3>
                                        <!-- <img src="img/logo.png" alt="Logo"> -->
                                    </a>
                                    <p class="mb-4 text-white">Dolor amet sit justo amet elitr clita ipsum elitr
                                        est.Lorem
                                        ipsum dolor sit amet, consectetur adipiscing...</p>
                                    <div class="footer-btn d-flex">
                                        <a class="btn btn-md-square rounded-circle me-3" href="#"><i
                                                class="fab fa-facebook-f"></i></a>
                                        <a class="btn btn-md-square rounded-circle me-3" href="#"><i
                                                class="fab fa-twitter"></i></a>
                                        <a class="btn btn-md-square rounded-circle me-3" href="#"><i
                                                class="fab fa-instagram"></i></a>
                                        <a class="btn btn-md-square rounded-circle me-0" href="#"><i
                                                class="fab fa-linkedin-in"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xl-3">
                                <div class="footer-item">
                                    <h4 class="mb-4 text-white">Useful Links</h4>
                                    <a href="#"><i class="fas fa-angle-right me-2"></i> About Us</a>
                                    <a href="#"><i class="fas fa-angle-right me-2"></i> Features</a>
                                    <a href="#"><i class="fas fa-angle-right me-2"></i> Services</a>
                                    <a href="#"><i class="fas fa-angle-right me-2"></i> FAQ's</a>
                                    <a href="#"><i class="fas fa-angle-right me-2"></i> Blogs</a>
                                    <a href="#"><i class="fas fa-angle-right me-2"></i> Contact</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xl-4">
                                <div class="footer-item">
                                    <h4 class="mb-4 text-white">Instagram</h4>
                                    <div class="row g-3">
                                        <div class="col-4">
                                            <div class="rounded footer-instagram">
                                                <img src="img/instagram-footer-1.jpg" class="img-fluid w-100"
                                                    alt="">
                                                <div class="footer-search-icon">
                                                    <a href="img/instagram-footer-1.jpg"
                                                        data-lightbox="footerInstagram-1" class="my-auto"><i
                                                            class="text-white fas fa-link"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="rounded footer-instagram">
                                                <img src="img/instagram-footer-2.jpg" class="img-fluid w-100"
                                                    alt="">
                                                <div class="footer-search-icon">
                                                    <a href="img/instagram-footer-2.jpg"
                                                        data-lightbox="footerInstagram-2" class="my-auto"><i
                                                            class="text-white fas fa-link"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="rounded footer-instagram">
                                                <img src="img/instagram-footer-3.jpg" class="img-fluid w-100"
                                                    alt="">
                                                <div class="footer-search-icon">
                                                    <a href="img/instagram-footer-3.jpg"
                                                        data-lightbox="footerInstagram-3" class="my-auto"><i
                                                            class="text-white fas fa-link"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="rounded footer-instagram">
                                                <img src="img/instagram-footer-4.jpg" class="img-fluid w-100"
                                                    alt="">
                                                <div class="footer-search-icon">
                                                    <a href="img/instagram-footer-4.jpg"
                                                        data-lightbox="footerInstagram-4" class="my-auto"><i
                                                            class="text-white fas fa-link"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="rounded footer-instagram">
                                                <img src="img/instagram-footer-5.jpg" class="img-fluid w-100"
                                                    alt="">
                                                <div class="footer-search-icon">
                                                    <a href="img/instagram-footer-5.jpg"
                                                        data-lightbox="footerInstagram-5" class="my-auto"><i
                                                            class="text-white fas fa-link"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="rounded footer-instagram">
                                                <img src="img/instagram-footer-6.jpg" class="img-fluid w-100"
                                                    alt="">
                                                <div class="footer-search-icon">
                                                    <a href="img/instagram-footer-6.jpg"
                                                        data-lightbox="footerInstagram-6" class="my-auto"><i
                                                            class="text-white fas fa-link"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pt-5" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                        <div class="row g-0">
                            <div class="col-12">
                                <div class="row g-4">
                                    <div class="col-lg-6 col-xl-4">
                                        <div class="d-flex">
                                            <div class="p-4 text-white rounded btn-xl-square bg-primary me-4">
                                                <i class="fas fa-map-marker-alt fa-2x"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-white">Address</h4>
                                                <p class="mb-0">123 Street New York.USA</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-xl-4">
                                        <div class="d-flex">
                                            <div class="p-4 text-white rounded btn-xl-square bg-primary me-4">
                                                <i class="fas fa-envelope fa-2x"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-white">Mail Us</h4>
                                                <p class="mb-0">alkhairiyah.university@gmail.com</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-xl-4">
                                        <div class="d-flex">
                                            <div class="p-4 text-white rounded btn-xl-square bg-primary me-4">
                                                <i class="fa fa-phone-alt fa-2x"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-white">Telephone</h4>
                                                <p class="mb-0">(+012) 3456 7890</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="footer-item">
                        <h4 class="mb-4 text-white">Newsletter</h4>
                        <p class="mb-3 text-white">Dolor amet sit justo amet elitr clita ipsum elitr est.Lorem ipsum
                            dolor
                            sit amet, consectetur adipiscing elit.</p>
                        <div class="mb-4 position-relative rounded-pill">
                            <input class="py-3 form-control rounded-pill w-100 ps-4 pe-5" type="text"
                                placeholder="Enter your email">
                            <button type="button"
                                class="top-0 py-2 mt-2 btn btn-primary rounded-pill position-absolute end-0 me-2">SignUp</button>
                        </div>
                        <div class="flex-shrink-0 d-flex">
                            <div class="footer-btn">
                                <a href="#" class="btn btn-lg-square rounded-circle position-relative wow tada"
                                    data-wow-delay=".9s">
                                    <i class="fa fa-phone-alt fa-2x"></i>
                                    <div class="position-absolute" style="top: 2px; right: 12px;">
                                        <span><i class="fa fa-comment-dots text-secondary"></i></span>
                                    </div>
                                </a>
                            </div>
                            <div class="flex-shrink-0 d-flex flex-column ms-3">
                                <span>Call to Our Experts</span>
                                <a href="tel:02547877057"><span class="text-white">Free: (0254) 7877057</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <x-copyright />
    <x-back-to-top />

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('lib/lightbox/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        window.addEventListener('scroll-to-search', () => {
            const el = document.getElementById('search-hero');
            if (el) {
                el.scrollIntoView({
                    behavior: 'instant',
                    block: 'start'
                });
            }
        });
    </script>
</body>

</html>
