@extends('layouts.landing')

@section('hero')
    <section id="hero" class="hero">

        <div class="info d-flex align-items-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h2 data-aos="fade-down"><span>Sidasi Tampan</span></h2>
                        <p data-aos="fade-up">Digitalisasi Data dan Informasi Tahapan Mengenai Perencanaan Anggaran.</p>
                        <a data-aos="fade-up" data-aos-delay="200" href="{{ route('login') }}" class="btn-get-started">Get
                            Started</a>
                        <a data-aos="fade-up" data-aos-delay="200" href="{{ asset('landing/E-Book.pdf') }}" target="_blank"
                            class="btn-get-started">E-Book</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="hero-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">

            <div class="carousel-item active" style="background-image: url({{ asset('landing/assets/img/logo.JPEG') }})">
            </div>
            <div class="carousel-item" style="background-image: url({{ asset('landing/assets/img/logo.JPEG') }})">
            </div>
            <div class="carousel-item" style="background-image: url({{ asset('landing/assets/img/logo.JPEG') }})">
            </div>
            <div class="carousel-item" style="background-image: url({{ asset('landing/assets/img/logo.JPEG') }})">
            </div>
            <div class="carousel-item" style="background-image: url({{ asset('landing/assets/img/logo.JPEG') }})">
            </div>

            <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
            </a>

            <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
            </a>

        </div>

    </section><!-- End Hero Section -->
@endsection

@section('content')
    <!-- ======= Get Started Section ======= -->
    <section id="get-started" class="get-started section-bg">
        <div class="container">

            <div class="row justify-content-between gy-4">

                <div class="col-lg-6 d-flex align-items-center" data-aos="fade-up">
                    <div class="content">
                        <h3>SIDASI TAMPAN</h3>
                        <p>Digitalisasi Data dan Informasi Tahapan Mengenai Perencanaan Anggaran.
                        <p>SIPD merupakan aplikasi umum dalam pengelolaan keuangan dari mulai perencanaan, penganggaran,
                            penatausahaan dan pertanggungjawaban.</p>
                        <p>Aplikasi SIPD yang masih baru, belum banyak menyediakan fitur yang diharapkan sehingga terdapat
                            ruang oportunis bagi SIDASI TAMPAN untuk menyajikan data dan informasi dalam Perencanaan
                            Anggaran.</p>
                        <p>Input minimalis dan memaksimalkan upload data dari SIPD merupakan salah satu keunggulan SIDASI
                            TAMPAN</p>
                        <p>Mengedepankan tampilan grafik agar lebih mudah dalam pemahaman data</p>
                        <p>Video Tutorial penggunaan aplikasi SIPD dan aplikasi lain terkait penganggaran menjadi salah satu
                            fitur unngulan dari SIDASI TAMPAN</p>
                        <p>Output data series memberikan ruang untuk dapat digunakan oleh aplikasi lain dalam input data /
                            penyajian lain</p>
                    </div>
                </div>

                <div class="col-lg-5" data-aos="fade">
                    <img width="100%" height="100%" src="{{ asset('landing/assets/img/logo.JPEG') }}">
                </div><!-- End Quote Form -->

            </div>

        </div>
    </section><!-- End Get Started Section -->



    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
        <div class="container" data-aos="fade-up">

            <div class="section-header">
                <h2>Fitur Aplikasi</h2>
                <p>Aplikasi SIDASI TAMPAN memiliki fitur-fitur :</p>
            </div>

            <div class="row gy-4">

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-item  position-relative">
                        <div class="icon">
                            <i class="fa-solid fa-mountain-city"></i>
                        </div>
                        <h3>Upload Data Penganggaran</h3>
                        <p>Upload data per tahapan penganggaran, baik dari Aplikasi SIPD maupun Aplikasi SIPD-RI.</p>

                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-item position-relative">
                        <div class="icon">
                            <i class="fa-solid fa-arrow-up-from-ground-water"></i>
                        </div>
                        <h3>Upload Data Realisasi</h3>
                        <p>Upload data realiasi dapat dilakukan oleh SKPD dari Aplikasi SIPD Penatausahaan sehingga data
                            yang disajikan merupakan data real time pada saat itu yang diambil dari SIPD.</p>
                        <a href="javascript:;" class="readmore stretched-link">Learn more <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-item position-relative">
                        <div class="icon">
                            <i class="fa-solid fa-compass-drafting"></i>
                        </div>
                        <h3>Grafik Anggaran By Rekening</h3>
                        <p>Menampilkan grafik penganggaran secara akumulasi kota atau per SKPD yang ditampilkan berdasarkan
                            Badan Akun Standar (BAS).</p>
                        <a href="javascript:;" class="readmore stretched-link">Learn more <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-item position-relative">
                        <div class="icon">
                            <i class="fa-solid fa-trowel-bricks"></i>
                        </div>
                        <h3>Grafik Anggaran By Urusan</h3>
                        <p>Menampilkan grafik penganggaran secara akumulasi kota atau per SKPD yang ditampilkan berdasarkan
                            Urusan, Program, Kegiatan, sampai dengan Sub Kegiatan</p>
                        <a href="javascript:;" class="readmore stretched-link">Learn more <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="service-item position-relative">
                        <div class="icon">
                            <i class="fa-solid fa-helmet-safety"></i>
                        </div>
                        <h3>Grafik Data Sanding Anggaran per Tahapan</h3>
                        <p>Menampilkan grafik penganggaran yang menyandingkan data Per Tahapan Penganggaran</p>
                        <a href="javascript:;" class="readmore stretched-link">Learn more <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="service-item position-relative">
                        <div class="icon">
                            <i class="fa-solid fa-arrow-up-from-ground-water"></i>
                        </div>
                        <h3>Data Sanding Anggaran per Tahapan</h3>
                        <p>Download data penganggaran yang menyandingkan data Per Tahapan Penganggaran untuk dapat
                            dimanfaatkan oleh aplikasi lain dalam input data / penyajian lain </p>
                        <a href="javascript:;" class="readmore stretched-link">Learn more <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-item position-relative">
                        <div class="icon">
                            <i class="fa-solid fa-trowel-bricks"></i>
                        </div>
                        <h3>Mandatori Spending</h3>
                        <p>Menampilkan data pemenuhan anggaran wajib telah dipenuhi dalam tahapan penganggaran tertentu.
                            Sehingga dapat diketahui sudah terpenuhi / belum</p>
                        <a href="javascript:;" class="readmore stretched-link">Learn more <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->


                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="service-item position-relative">
                        <div class="icon">
                            <i class="fa-solid fa-arrow-up-from-ground-water"></i>
                        </div>
                        <h3>Data Prioritas</h3>
                        <p>Menampilkan data Prioritas sehingga dapat diketahui besaran anggaran prioritas dalam tahapan
                            tertentu. Misalnya terkait anggaran Bangkom, Inflasi, Kemiskinan Ekstrim, Dll.</p>
                        <a href="javascript:;" class="readmore stretched-link">Learn more <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->
            </div>

        </div>
    </section><!-- End Services Section -->


    <!-- ======= Features Section ======= -->
    <section id="features" class="features section-bg">
        <div class="container" data-aos="fade-up">

            <ul class="nav nav-tabs row  g-2 d-flex">

                <li class="nav-item col-3">
                    <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#tab-1">
                        <h4>Penganggaran</h4>
                    </a>
                </li><!-- End tab nav item -->

                <li class="nav-item col-3">
                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-2">
                        <h4>Realisasi</h4>
                    </a><!-- End tab nav item -->

                <li class="nav-item col-3">
                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-3">
                        <h4>Mandatory</h4>
                    </a>
                </li><!-- End tab nav item -->

                <li class="nav-item col-3">
                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-4">
                        <h4>Prioritas</h4>
                    </a>
                </li><!-- End tab nav item -->

            </ul>

            <div class="tab-content">

                <div class="tab-pane active show" id="tab-1">
                    <div class="row">
                        <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center"
                            data-aos="fade-up" data-aos-delay="100">
                            <h3>Penganggaran</h3>
                            <p class="fst-italic">
                                Tahapan penganggaran di SIDASI TAMPAN dapat dibuat sesuai dengan kebutuhan tanpa melihat
                                tahapan di SIPD. Data dapat diambil langsung dari SIPD Penganggaran untuk diupload di
                                Aplikasi SIDASI TAMPAN
                            </p>
                            <p>Tahapan yang sudah disimpan dapat dimanfaatkan untuk</p>

                            <ul>
                                <li><i class="bi bi-check2-all"></i> Penelaahan data penganggaran, apakah telah sesuai
                                    dengan yang seharusnya.</li>
                                <li><i class="bi bi-check2-all"></i> Perbandingan data antar tahapan, sehingga dapat
                                    diketahui penambahan / pengurangan data s.d dengan sub kegiatan dan rekening.</li>

                            </ul>
                        </div>
                        <div class="col-lg-6 order-1 order-lg-2 text-center" data-aos="fade-up" data-aos-delay="200">
                            <img src="{{ asset('landing/assets/img/anggaran.PNG') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div><!-- End tab content item -->

                <div class="tab-pane" id="tab-2">
                    <div class="row">
                        <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                            <h3>Realisasi</h3>
                            <p class="fst-italic">
                                Data Realisasi anggaran dapat diupload dari SIPD Penatausahaaan. Upload dilakukan oleh SKPD,
                                sehingga apabila memerlukan data realisasi yang real time pada saat itu, SKPD dapat langsung
                                upload data dari SIPD.
                            </p>
                            <p>Fungsi menu realisasi anggaran ialah :</p>
                            <ul>
                                <li><i class="bi bi-check2-all"></i> Belum adanya fitur lengkap dari SIPD Penatausahaan
                                    untuk menjelaskan realisasi dan sisa anggaran yang tersedia</li>
                                <li><i class="bi bi-check2-all"></i> Untuk memberikan gambaran PPTK agar dapat segera
                                    melaksanakan kegiatan yang memang sudah seharusnya dilakukan</li>
                                <li><i class="bi bi-check2-all"></i> Memberikan gambaran sisa anggaran dalam proses
                                    penyusunan perubahan APBD, termasuk APBD Pergeseran.</li>

                            </ul>
                        </div>
                        <div class="col-lg-6 order-1 order-lg-2 text-center">
                            <img src="{{ asset('landing/assets/img/realisasi.PNG') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div><!-- End tab content item -->

                <div class="tab-pane" id="tab-3">
                    <div class="row">
                        <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                            <h3>Pemenuhan anggaran wajib</h3>
                            <p>Anggaran wajib harus dipenuhi berdasarkan ketentuan perundang-undangan. Untuk memberikan
                                gambaran berapa besaran dari anggaran wajib yang harus dipenuhi dan berapa besaran
                                kekurangan anggaran yang harus ditambahkan jika belum memenuhi ketentuan dimaksud</p>
                            <p>Pemenuhan anggaran wajib diantaranya :</p>
                            <ul>
                                <li><i class="bi bi-check2-all"></i> Fungsi Pendidikan.</li>
                                <li><i class="bi bi-check2-all"></i> Fungsi Kesehatan</li>
                                <li><i class="bi bi-check2-all"></i> Infrastruktur</li>
                                <li><i class="bi bi-check2-all"></i> Pendidikan/ Pelatihan</li>
                                <li><i class="bi bi-check2-all"></i> Pengawasan</li>
                            </ul>

                        </div>
                        <div class="col-lg-6 order-1 order-lg-2 text-center">
                            <img src="{{ asset('landing/assets/img/mandatori.PNG') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div><!-- End tab content item -->

                <div class="tab-pane" id="tab-4">
                    <div class="row">
                        <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                            <h3>Program Prioritas</h3>
                            <p class="fst-italic">
                                Program Prioritas dapat ditambahkan sesuai dengan kebutuhan. Mengingat perlu adanya
                                pengawalan lebih untuk program prioritas, maka perlu ada pendefinisian program prioritas
                                dimaksud pada SKPD, Program, Kegiatan, Sub Kegiatan dan rekening tertentu, sehingga
                                pengawalan tiap tahapan dapat dilakukan.
                            </p>
                            <p>Contoh bentuk program prioritas diantaranya </p>
                            <ul>
                                <li><i class="bi bi-check2-all"></i>Pengembangan Kompetensi.</li>
                                <li><i class="bi bi-check2-all"></i> Penanggulangan Kemiskinan Ekstrim</li>
                                <li><i class="bi bi-check2-all"></i> Penanganan Inflasi</li>
                                <li><i class="bi bi-check2-all"></i> Pengurangan Pengangguran</li>
                            </ul>
                        </div>
                        <div class="col-lg-6 order-1 order-lg-2 text-center">
                            <img src="{{ asset('landing/assets/img/prioritas.PNG') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div><!-- End tab content item -->

            </div>

        </div>
    </section><!-- End Features Section -->

    <!-- ======= Our Projects Section ======= -->
    <section id="projects" class="projects">
        <div class="container" data-aos="fade-up">

            <div class="section-header">
                <h2>Video Tutorial</h2>
                <p>Video Tutorial Aplikasi</p>
            </div>

            <div class="portfolio-isotope" data-portfolio-filter="*" data-portfolio-layout="masonry"
                data-portfolio-sort="original-order">

                <ul class="portfolio-flters" data-aos="fade-up" data-aos-delay="100">
                    <li data-filter="*" class="filter-active">All</li>
                    <li data-filter=".filter-SIDASI-TAMPAN">SIDASI TAMPAN</li>
                    <li data-filter=".filter-SIPD-PENGANGGARAN">SIPD Penganggaran</li>
                    <li data-filter=".filter-SIPD-RI-PENGANGGARAN">SIPD-RI Penganggaran</li>
                    <li data-filter=".filter-SIPD-RI-PENATAUSAHAAN">SIPD-Penatausahaan</li>
                    <li data-filter=".filter-Lain-lain">Lain-lain</li>
                </ul><!-- End Projects Filters -->

                <div class="row gy-4 portfolio-container" data-aos="fade-up" data-aos-delay="200">
                    @foreach ($sosmeds as $sosmed)
                        <div
                            class="col-lg-4 col-md-6 portfolio-item filter-{{ str_replace(' ', '-', $sosmed->category) }}">
                            <div class="d-flex justify-content-center"
                                style="position: relative;height: 570px;width:100%;">
                                <iframe src="https://www.tiktok.com/embed/{{ explode('video/', $sosmed->link)[1] }}"
                                    style="height: 100%;width:100%" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    @endforeach

                </div><!-- End Projects Container -->

            </div>

        </div>
    </section><!-- End Our Projects Section -->
@endsection
