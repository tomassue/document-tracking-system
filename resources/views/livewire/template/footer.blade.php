<div>
    <style>
        /* FOOTER */
        .footer {
            font-family: "Montserrat", sans-serif;
        }

        .brand-container {
            width: 100%;
            max-width: 150px;
            margin: 10px auto;
            line-height: 0;
            text-align: left;
            display: inline-block;
        }

        .link {
            background-color: #FACD06;
            margin-top: 20px;
            padding: 15px;
            border-radius: 20px;
            color: #063B47;
            box-shadow: 3px 3px #888888;
        }

        .link2 {
            background-color: #5B9EB4;
            margin-top: 20px;
            padding: 15px;
            border-radius: 20px;
            color: #F3F3EE;
            box-shadow: 3px 3px #888888;
        }

        .other-systems {
            margin-top: 12px;
            font-weight: 300 !important;
        }

        .other-systems h6 {
            color: #2F9ACB;
            font-weight: 600;
        }

        .footer-links {
            padding-left: 0;
            list-style: none;
        }

        .footer-links a {
            color: #737373;
        }

        .footer-links li {
            display: block;
        }

        .site-footer h6 {
            font-size: 16px;
            text-transform: uppercase;
            margin-top: 5px;
            letter-spacing: 2px;
        }

        .footer-links li a {
            color: black;
            text-decoration: none;
            outline: none;
            transition: all 0.2s;
        }

        .image-block a {
            text-decoration: none;
            outline: none;
            transition: all 0.2s;
        }

        .separator {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 2px solid rgba(0, 0, 0, 0.1);
        }

        #myBtn {
            background: lightgray;
            display: block;
            color: #878038;
            width: 100px;
            border: none;
            border-radius: 40px;
            padding: 1px 0;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 32px;
            outline: none;
            font-size: 8pt;
        }

        .client-info {
            font-size: 11px;
            line-height: 13px;
        }

        /* END FOOTER */
    </style>
    <!-- partial:./partials/_footer.html -->
    <footer id="footer" class="footer text-start" style="background-color: #fff;">
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-md-12">
                    <footer class="site-footer">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-2">
                                    <div class="client-info">
                                        <h5>Document Tracking System</h5>
                                        <p>
                                            This system dsdsfdjskbhcbshduavyefuvaewy.
                                        </p>
                                        <p>
                                            <br />
                                            <strong>Developed by:</strong> CMISID Team /
                                            </span>
                                        </p>
                                        {{-- <p class="fw-bold pb-3">The City Planning and Development Office (CPDO)</p>
                                <p>
                                    Formulates and integrates economic, social, physical and other development objectives and policies. Prepares comprehensive plans and similar development planning documents. Monitors and evaluates the implementation of different programs, projects and activities in the city in accordance with the approved development plan.
                                </p>                     --}}
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-3 col-lg-2 other-systems">
                                    <!-- <h6>other Related Systems</h6> -->
                                    <ul class="footer-links">
                                        <!-- <li><a href="http://119.93.152.91/dtr/" style="color: #5390ad;">Attendance Management System</a></li> -->
                                        <br />
                                        <li>If you have issues encountered and inquiries:<a href="https://services.cagayandeoro.gov.ph/helpdesk/" style="color: #5390ad;">
                                                <br />
                                                CMISID Helpdesk</a></li>
                                    </ul>
                                </div>
                                <div class="col-xs-6 col-md-3 col-lg-4 other-systems">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12 col-xl-12">
                                            <div class="brand-container" style="width: fit-content;">
                                                <img src="{{ asset('images/cdofull.png') }}" style="float:left; height: 50px;">
                                            </div>
                                            <a href="https://cagayandeoro.gov.ph/" target="_blank" class='link' name='link' style="float:right; margin-top: 0px;">Visit Official Website</a>
                                        </div>
                                        <div class="col-md-12 col-lg-12 col-xl-12">
                                            <div class="brand-container" style="width: fit-content;">
                                                <img src="{{ asset('images/risebig.png') }}" style="height: 70px;">
                                            </div>
                                            <a href="https://cagayandeoro.gov.ph/index.php/news/the-city-mayor/rise1.html" target="_blank" class='link2' name='link2' style="float:right;">Learn RISE Platform</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 separator"></div>
                            </div>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-11 col-sm-6 col-xs-12">
                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <img src="{{ asset('images/ict.png') }}">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <span>Powered by: City Management Information Systems and Innovation Department</span><br>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <span>&nbsp;&nbsp;<div class="fb-like fb_iframe_widget" data-href="https://www.facebook.com/City-Management-Information-Systems-Office-LGU-CdeO-568493593557935/" data-width="" data-layout="button_count" data-action="like" data-size="small" data-share="true" data-show-faces="false"></div></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-6 col-xs-12 version">
                                        Version 1.0
                                    </div>
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </footer>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_LA/sdk.js#xfbml=1&version=v14.0" nonce="wcxFHnry"></script>
    <script>
        function toggleFooter() {
            var dots = document.getElementById("dots");
            var more = document.getElementById("more");
            var myBtn = document.getElementById("myBtn");
            if (more.style.display === "none") {
                more.style.display = "block";
                myBtn.textContent = "Read Less";
                dots.style.display = "none";
            } else {
                more.style.display = "none";
                myBtn.textContent = "Read More";
                dots.style.display = "block";
            }
        }
    </script>
    <!-- partial -->
</div>