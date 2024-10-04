<!DOCTYPE html>
<html lang="en" >
    <!--begin::Head-->
    <head>
        <title>Sign In</title>
        <meta charset="utf-8"/>
        <meta name="description" content="employee information management system, e201"/>
        <meta name="keywords" content="e201, employee information, ems"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="shortcut icon" href="<?=base_url('assets/img')?>/<?=$logo['File']?>"/>

        <!--begin::Fonts(mandatory for all pages)-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>        <!--end::Fonts-->
        <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
                <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
                <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css"/>
            <!--end::Global Stylesheets Bundle-->   
        <script>
            // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
            if (window.top != window.self) {
                window.top.location.replace(window.self.location.href);
            }
        </script>
        <style>
            body {
                background-image: url('assets/img/background.jpg');
            }

            [data-bs-theme="dark"] body {
                background-image: url('assets/img/background.jpg');
            }
        </style>
    </head>
    <!--end::Head-->

    <!--begin::Body-->
    <body  id="kt_body"  class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center" >
        <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;

        if ( document.documentElement ) {
            if ( document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if ( localStorage.getItem("data-bs-theme") !== null ) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }			
            }

            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }

            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }            
    </script>
        
        <!--begin::Root-->
<div class="d-flex flex-column flex-root" id="kt_app_root">
    <!--begin::Page bg image-->

<!--begin::Authentication - Sign-in -->
<div class="d-flex flex-column flex-lg-row flex-column-fluid">
    <!--begin::Aside-->
    <div class="d-flex flex-lg-row-fluid">
        <!--begin::Content-->
        <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100"> 
            <!--begin::Image-->                
            <img class="theme-light-show mx-auto mw-100 mb-10 mb-lg-20" height="250px;" src="assets/img/cover-photo.png" alt=""/>                 
            <!--end::Image-->

            <!--begin::Title-->
            <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7"> 
            Online E201
            </h1>  
            <!--end::Title-->

            <!--begin::Text-->
            <div class="text-gray-600 fs-base text-center fw-semibold">
                Online Employee Information Management System for <br/>Gabales Engineering Services
            </div>
            <!--end::Text-->
        </div>
        <!--end::Content-->
    </div>
    <!--begin::Aside-->

    <!--begin::Body-->
    <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
        <!--begin::Wrapper-->
        <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                <!--begin::Wrapper-->
                <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
                    <!--begin::Form-->
                    <form class="form w-100" method="POST" novalidate="novalidate" id="kt_sign_in_form" action="<?=base_url('/employeeAuth')?>">
                        <!--begin::Heading-->
                        <div class="text-center mb-11">
                            <!--begin::Title-->
                            <h1 class="text-gray-900 fw-bolder mb-3">
                                Employee Portal
                            </h1>
                            <!--end::Title-->

                            <!--begin::Subtitle-->
                            <div class="text-gray-500 fw-semibold fs-6">
                                Gabales Engineering Services
                            </div>
                            <!--end::Subtitle--->
                        </div>
                        <?php if(!empty(session()->getFlashdata('fail'))) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?= session()->getFlashdata('fail'); ?>
                            </div>
                        <?php endif; ?>
                        <!--begin::Heading-->
                        <!--begin::Input group--->
                        <div class="fv-row mb-8">
                            <!--begin::Email-->
                            <input type="text" placeholder="Employee ID" name="username" autocomplete="off" class="form-control bg-transparent" required/> 
                            <!--end::Email-->
                        </div>

                        <!--end::Input group--->
                        <div class="fv-row mb-3">    
                            <!--begin::Password-->
                            <input type="password" placeholder="PIN" name="password" autocomplete="off" class="form-control bg-transparent" required/>
                            <!--end::Password-->
                        </div>
                        <!--end::Input group--->   

                        <!--begin::Submit button-->
                        <div class="d-grid mb-10">
                            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                
                            <!--begin::Indicator label-->
                            <span class="indicator-label">
                                Sign In
                            </span>
                            <!--end::Indicator label-->        
                            </button>
                        </div>
                        <!--end::Submit button-->
                    </form>
                    <!--end::Form-->     
                    <p>Back to <a href="<?=site_url('/')?>">Home</a></p>
                </div>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Body-->
</div>
<!--end::Authentication - Sign-in-->
</div>
<!--end::Root-->       
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
            <script src="assets/plugins/global/plugins.bundle.js"></script>
            <script src="assets/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
    </body>
    <!--end::Body-->
</html>