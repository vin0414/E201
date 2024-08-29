<!DOCTYPE html>
<html lang="en" >
    <!--begin::Head-->
    <head>
        <title>E201 - View Employee</title>
        <meta charset="utf-8"/>
        <meta name="description" content="employee information management system, e201"/>
        <meta name="keywords" content="e201, employee information, ems"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="shortcut icon" href="<?=base_url('assets/img/logo.png')?>"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/> 
        <link href="<?=base_url('assets/plugins/custom/datatables/datatables.bundle.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?=base_url('assets/plugins/global/plugins.bundle.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?=base_url('assets/css/style.bundle.css')?>" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            thead,th{background-color:#0096ff;}
        </style>
    </head>
    <body  id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-header-stacked="true" data-kt-app-header-primary-enabled="true" data-kt-app-header-secondary-enabled="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true"  class="app-default" >
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
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">     
        <div id="kt_app_header" class="app-header ">
            <div class="app-header-primary">
                <div class="app-container  container-fluid d-flex align-items-stretch justify-content-between " id="kt_app_header_primary_container">
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="d-flex">
                            <div class="app-header-logo d-flex flex-center gap-2 me-lg-15">
                                <button class="btn btn-icon btn-sm btn-custom d-flex d-lg-none ms-n2" id="kt_app_header_menu_toggle">
                                    <i class="fa-solid fa-bars"></i>	
                                </button>
                                <a href="HR/overview">
                                    <img alt="Logo" src="<?=base_url('assets/img/logo.png')?>" class="mh-25px"/>
                                </a>
                            </div>
                            <div class="d-flex align-items-stretch" id="kt_app_header_menu_wrapper">
                                <div 
                                    class="app-header-menu app-header-mobile-drawer align-items-stretch" 

                                    data-kt-drawer="true"
                                    data-kt-drawer-name="app-header-menu"
                                    data-kt-drawer-activate="{default: true, lg: false}"
                                    data-kt-drawer-overlay="true"
                                    data-kt-drawer-width="{default:'200px', '300px': '250px'}"
                                    data-kt-drawer-direction="start"
                                    data-kt-drawer-toggle="#kt_app_header_menu_toggle" 

                                    data-kt-swapper="true"
                                    data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
                                    data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_menu_wrapper'}"
                                >   
                                    <!--begin::Menu-->
                                    <div 
                                        class="
                                            menu 
                                            menu-rounded 
                                            menu-column 
                                            menu-lg-row 
                                            menu-active-bg 
                                            menu-title-gray-700 
                                            menu-state-gray-900 
                                            menu-icon-gray-500
                                            menu-arrow-gray-500 
                                            menu-state-icon-primary
                                            menu-state-bullet-primary
                                            fw-semibold 
                                            fs-6 
                                            align-items-stretch 
                                            my-5 
                                            my-lg-0 
                                            px-2 
                                            px-lg-0
                                        " 
                                        id="#kt_app_header_menu" 
                                        data-kt-menu="true"
                                    >        
                                        <!--begin:Menu item-->
                                        <div  data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"  class="menu-item here show menu-here-bg menu-lg-down-accordion me-0 me-lg-2" ><!--begin:Menu link-->
                                            <span class="menu-link"  >
                                                <span  class="menu-title" >Dashboards</span>
                                            </span>
                                            <!--end:Menu link--><!--begin:Menu sub-->
                                            <div  class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-850px" ><!--begin:Dashboards menu-->
                                            </div><!--end:Menu sub-->
                                        </div><!--end:Menu item-->
                                        <?php if(session()->get('role')=="Administrator"){ ?>
										<div  data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"  class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2" >
											<span class="menu-link"><span  class="menu-title" >Apps</span>
                                            <span  class="menu-arrow d-lg-none" ></span></span>
											<div  class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px" >
                                                <!--begin:Menu item-->
                                                <div  data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"  class="menu-item menu-lg-down-accordion" >
                                                    <!--begin:Menu link-->
                                                    <span class="menu-link"  >
                                                        <span  class="menu-icon" ><i class="fa-solid fa-user-gear"></i></span>
                                                        <span  class="menu-title" >User Management</span><span  class="menu-arrow" ></span>
                                                    </span><!--end:Menu link-->
                                                    <!--begin:Menu sub-->
                                                    <div  class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px" >
                                                        <div  class="menu-item" ><!--begin:Menu link-->
                                                            <a class="menu-link"  href="<?=site_url('HR/users')?>">
                                                                <span  class="menu-bullet" ><span class="bullet bullet-dot"></span></span>
                                                                <span  class="menu-title" >User List</span>
                                                            </a><!--end:Menu link-->
                                                        </div><!--end:Menu item-->
                                                        <div  class="menu-item" ><!--begin:Menu link-->
                                                            <a class="menu-link"  href="<?=site_url('HR/new-account')?>"  >
                                                                <span  class="menu-bullet" ><span class="bullet bullet-dot"></span></span>
                                                                <span  class="menu-title" >Add User</span>
                                                            </a><!--end:Menu link-->
                                                        </div><!--end:Menu item-->
                                                    </div><!--end:Menu sub-->
                                                </div><!--end:Menu item-->
                                                <div  class="menu-item" ><!--begin:Menu link-->
                                                    <a class="menu-link"  href="<?=site_url('HR/new-employee')?>"  >
                                                        <span  class="menu-icon" ><i class="fa-solid fa-user-tie"></i></span>
                                                        <span  class="menu-title" >New Employee</span>
                                                    </a><!--end:Menu link-->
                                                </div><!--end:Menu item-->
                                                <div  class="menu-item" ><!--begin:Menu link-->
                                                    <a class="menu-link"  href="<?=site_url('HR/Memo')?>"  >
                                                        <span  class="menu-icon" ><i class="fa-solid fa-envelope-open-text"></i></span>
                                                        <span  class="menu-title" >Memorandum</span>
                                                    </a><!--end:Menu link-->
                                                </div><!--end:Menu item-->
                                                <div  class="menu-item" ><!--begin:Menu link-->
                                                    <a class="menu-link"  href="<?=site_url('HR/Evaluation')?>"  >
                                                        <span  class="menu-icon" ><i class="fa-solid fa-chart-simple"></i></span>
                                                        <span  class="menu-title" >Evaluation</span>
                                                    </a><!--end:Menu link-->
                                                </div><!--end:Menu item-->
                                                <div  class="menu-item" ><!--begin:Menu link-->
                                                    <a class="menu-link"  href="<?=site_url('HR/logs')?>">
                                                        <span  class="menu-icon" ><i class="fa-regular fa-clipboard"></i></span>
                                                        <span  class="menu-title" >System Logs<span>
                                                    </a><!--end:Menu link-->
                                                </div><!--end:Menu item-->
                                            </div><!--end:Menu sub-->
                                        </div><!--end:Menu item-->
                                        <?php } ?>
                                    </div>
                                    <!--end::Menu-->
                                </div>
                                <!--end::Menu holder-->			
                            </div>
                    <!--end::Menu wrapper-->	
                </div>    
                    <!--begin::Navbar-->
                    <div class="app-navbar flex-shrink-0 gap-2">
                        <!--begin::Notifications-->
                        <div class="app-navbar-item ms-1">
                            <!--begin::Menu- wrapper-->
                            <div class="btn btn-sm btn-icon btn-custom h-35px w-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
								<i class="fa-regular fa-bell"></i>        
                            </div>
                            
                    <!--end::Menu-->        <!--end::Menu wrapper-->
                        </div>
                        <!--end::Notifications-->    
                        
                        <!--begin::User menu-->
                        <div class="app-navbar-item ms-1">
                            <!--begin::Menu wrapper-->
                            <div class="cursor-pointer symbol position-relative symbol-35px" 
                                data-kt-menu-trigger="{default: 'click', lg: 'hover'}" 
                                data-kt-menu-attach="parent" 
                                data-kt-menu-placement="bottom-end">
                                <img src="<?=base_url('assets/img/profile.png')?>" alt="user"/>

                                <span class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle mb-1 bottom-0 start-100 animation-blink"></span>
                            </div>
                            
                    <!--begin::User account menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-50px me-5">
                                    <img alt="Logo" src="<?=base_url('assets/img/profile.png')?>"/>
                                </div>
                                <!--end::Avatar-->

                                <!--begin::Username-->
                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-center fs-5">
                                        <?php echo session()->get('fullname') ?>
                                    </div>

                                    <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                        <?php echo session()->get('role') ?>              
                                    </a>
                                </div>
                                <!--end::Username-->
                            </div>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu separator-->
                        <div class="separator my-2"></div>
                        <!--end::Menu separator-->

                                <!--begin::Menu item-->
                            <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                                <a href="#" class="menu-link px-5">
                                    <span class="menu-title position-relative">
                                        Mode 

                                        <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
											<i class="fa-regular fa-sun"></i>                      
											<i class="fa-solid fa-moon"></i>                   
										</span>
                                    </span>
                                </a>

                                <!--begin::Menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                <span class="menu-icon" data-kt-element="icon">
									<i class="fa-regular fa-sun"></i>            
								</span>
                                <span class="menu-title">
                                    Light
                                </span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                <span class="menu-icon" data-kt-element="icon">
									<i class="fa-solid fa-moon"></i>            
								</span>
                                <span class="menu-title">
                                    Dark
                                </span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                <span class="menu-icon" data-kt-element="icon">
									<i class="fa-solid fa-computer"></i>           
								</span>
                                <span class="menu-title">
                                    System
                                </span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu-->

                            </div>
                            <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item px-5 my-1">
                            <a href="<?=site_url('HR/account')?>" class="menu-link px-5">
                                Account Settings
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="<?=site_url('/logout')?>" class="menu-link px-5">
                                Sign Out
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::User account menu-->
                            <!--end::Menu wrapper-->
                        </div>
                        <!--end::User menu--> 

                        <!--begin::Header menu toggle-->
                        <div class="app-navbar-item d-lg-none" title="Show header menu">
                            <button class="btn btn-sm btn-icon btn-custom h-35px w-35px" id="kt_header_secondary_mobile_toggle">
                                <i class="fa-brands fa-elementor"></i>      
                            </button>
                        </div>
                        <!--end::Header menu toggle-->

                        <!--begin::Header menu toggle-->
                        <div class="app-navbar-item d-lg-none me-n3" title="Show header menu">
                            <button class="btn btn-sm btn-icon btn-custom h-35px w-35px" id="kt_app_sidebar_mobile_toggle">
                                <i class="fa-brands fa-buffer"></i>      
                            </button>
                        </div>
                        <!--end::Header menu toggle-->
                    </div>
                    <!--end::Navbar-->
                    </div>
                </div>             
            </div>
            <div class="app-header-secondary  app-header-mobile-drawer "
                 data-kt-drawer="true" data-kt-drawer-name="app-header-secondary" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_header_secondary_mobile_toggle"                                  
                 data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'append'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header'}" >

                <!--begin::Header secondary wrapper-->
                <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                    <div class="app-header-secondary-menu-main d-flex flex-grow-lg-1 align-items-end pt-3 pt-lg-2 px-3 px-lg-0 w-auto overflow-auto flex-nowrap">
                        <div class="app-container  container-fluid ">
                            <!--begin::Main menu-->
                            <div 
                                class="
                                    menu 
                                    menu-rounded 
                                    menu-nowrap
                                    flex-shrink-0
                                    menu-row 
                                    menu-active-bg 
                                    menu-title-gray-700 
                                    menu-state-gray-900 
                                    menu-icon-gray-500
                                    menu-arrow-gray-500 
                                    menu-state-icon-primary
                                    menu-state-bullet-primary
                                    fw-semibold 
                                    fs-base 
                                    align-items-stretch 
                                " 
                                id="#kt_app_header_secondary_menu" 
                                data-kt-menu="true"
                            >        
                                <!--begin:Menu item-->
                                <div  class="menu-item " >
                                    <!--begin:Menu link-->
                                    <a class="menu-link"  href="<?=site_url('HR/overview')?>"><span  class="menu-title" >Overview</span></a>
                                    <!--end:Menu link-->
                                </div><!--end:Menu item-->
                                <div  class="menu-item" ><!--begin:Menu content-->
                                    <div  class="menu-content" ><div class="menu-separator"></div></div><!--end:Menu content-->
                                </div><!--end:Menu item-->
                                <!--begin:Menu item-->
                                <div  class="menu-item " >
                                    <!--begin:Menu link-->
                                    <a class="menu-link"  href="<?=site_url('HR/employee')?>"><span  class="menu-title" >Employee</span></a>
                                    <!--end:Menu link-->
                                </div><!--end:Menu item-->
                                <!--begin:Menu item-->
                                <div  class="menu-item" >
                                    <!--begin:Menu content--><div  class="menu-content" ><div class="menu-separator"></div></div>
                                    <!--end:Menu content-->
                                </div><!--end:Menu item-->
                                <!--begin:Menu item-->
                                <div  class="menu-item " >
                                    <!--begin:Menu link-->
                                    <a class="menu-link active"  href="javascript:void(0);"><span  class="menu-title" >View Employee</span></a>
                                    <!--end:Menu link-->
                                </div><!--end:Menu item-->
                                <!--begin:Menu item-->
                                <div  class="menu-item" >
                                    <!--begin:Menu content--><div  class="menu-content" ><div class="menu-separator"></div></div>
                                    <!--end:Menu content-->
                                </div><!--end:Menu item-->
                                <!--begin:Menu item-->
                                <div  class="menu-item " ><!--begin:Menu link-->
                                    <a class="menu-link"  href="<?=site_url('HR/performance')?>"><span  class="menu-title" >Performance</span></a>
                                    <!--end:Menu link-->
                                </div><!--end:Menu item-->
                                <!--begin:Menu item-->
                                <div  class="menu-item" ><!--begin:Menu content-->
                                    <div  class="menu-content" ><div class="menu-separator"></div></div><!--end:Menu content-->
                                </div>
                                <!--end:Menu item-->
                                <!--begin:Menu item-->
                                <div  class="menu-item " ><!--begin:Menu link-->
                                    <a class="menu-link"  href="<?=site_url('HR/report')?>"><span  class="menu-title" >Report</span></a>
                                    <!--end:Menu link-->
                                </div><!--end:Menu item-->
                                <!--begin:Menu item-->
                                <!--begin:Menu item-->
                                <div  class="menu-item flex-grow-1" ></div><!--end:Menu item-->
                                <!--begin:Menu item-->
                                <div  class="menu-item" >
                                    <!--begin:Menu content-->
                                    <div  class="menu-content" >
                                        <div class="menu-separator d-block d-lg-none"></div>
                                    </div><!--end:Menu content-->
                                </div><!--end:Menu item-->
                                <!--begin:Menu item-->
                                <div  class="menu-item" >
                                    <!--begin:Menu content-->
                                    <div  class="menu-content" ><div class="menu-separator"></div></div><!--end:Menu content-->
                                </div><!--end:Menu item-->
                                <!--begin:Menu item-->
                                <div  data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-end"  class="menu-item " >
                                    <!--begin:Menu link-->
                                    <span class="menu-link"  >
                                        <span  class="menu-title" >Tools</span><span  class="menu-arrow" ></span>
                                    </span><!--end:Menu link-->
                                    <!--begin:Menu sub-->
                                    <div  class="menu-sub menu-sub-dropdown px-lg-2 py-lg-4 w-150px w-lg-175px" >
                                        <!--begin:Menu item-->
                                        <div  class="menu-item" ><!--begin:Menu link-->
                                            <a class="menu-link"  href="">
                                                <span  class="menu-icon" ><i class="fa-solid fa-database"></i></span>
                                                <span  class="menu-title" >Back-Up & Restore</span>
                                            </a><!--end:Menu link-->
                                        </div><!--end:Menu item-->
                                    </div><!--end:Menu sub-->
                                </div><!--end:Menu item-->		
                            </div>
                            <!--end::Menu-->
                        </div>
                    </div>
                    
                    <div class="app-header-secondary-menu-sub d-flex align-items-stretch flex-grow-1">
                        <div class="app-container d-flex flex-column flex-lg-row align-items-stretch justify-content-lg-between  container-fluid ">
                            <!--begin::Main menu-->
                            <div 
                                class="
                                    menu 
                                    menu-rounded 
                                    menu-column 
                                    menu-lg-row 
                                    menu-active-bg 
                                    menu-title-gray-700 
                                    menu-state-gray-900 
                                    menu-icon-gray-500
                                    menu-arrow-gray-500 
                                    menu-state-icon-primary 
                                    menu-state-bullet-primary 
                                    fw-semibold 
                                    fs-base 
                                    align-items-stretch 
                                    my-2 
                                    my-lg-0 
                                    px-2 
                                    px-lg-0		
                                " 
                                id="#kt_app_header_tertiary_menu" 
                                data-kt-menu="true"
                            >        
                                <!--begin:Menu item-->
                                <div  class="menu-item" >
                                    <!--begin:Menu link-->
                                    <a class="menu-link active"  href="<?=site_url('HR/overview')?>">
                                        <span  class="menu-icon" >
                                            <i class="fa-solid fa-chart-simple"></i>
                                        </span>
                                        <span class="menu-title" >Summary</span>
                                    </a><!--end:Menu link-->
                                </div><!--end:Menu item-->	
                                <!--begin:Menu item-->
                                <div  class="menu-item" >
                                    <!--begin:Menu link-->
                                    <a class="menu-link"  href="">
                                        <span  class="menu-icon" >
                                            <i class="fa-solid fa-print"></i>
                                        </span>
                                        <span class="menu-title" >Print</span>
                                    </a><!--end:Menu link-->
                                </div><!--end:Menu item-->
                                <!--begin:Menu item-->
                                <div  class="menu-item" >
                                    <!--begin:Menu link-->
                                    <a class="menu-link"  href="">
                                        <span  class="menu-icon" >
                                            <i class="fa-solid fa-download"></i>
                                        </span>
                                        <span class="menu-title" >Export</span>
                                    </a><!--end:Menu link-->
                                </div><!--end:Menu item-->
                            </div>
                            <!--end::Menu-->	
                        </div>	
                    </div>
                </div>
                <!--end::Header secondary wrapper-->                                
            </div>
            <!--end::Header secondary-->
        
        </div>
        <!--end::Header-->        
        <!--begin::Wrapper-->
        <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">                                        
	    <!--begin::Sidebar-->
	    <div id="kt_app_sidebar" class="app-sidebar " 
		data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle"      
		>
				<!--begin::Sidebar wrapper-->
				<div
					id="kt_app_sidebar_wrapper"
					class="flex-grow-1 hover-scroll-y mt-9 mb-5 px-2 mx-3 ms-lg-7 me-lg-5"
					data-kt-scroll="true"
					data-kt-scroll-activate="true"
					data-kt-scroll-height="auto"     
					data-kt-scroll-dependencies="{default: false, lg: '#kt_app_header'}"
					data-kt-scroll-offset="5px"
				>
					<!--begin::Filter-->
				<div class="mb-4">
                    <a href="<?=site_url('HR/Memo/Upload')?>" class="btn btn-sm d-flex flex-stack border border-300 bg-gray-100i btn-color-gray-700 btn-active-color-gray-900 px-3 mb-2">               
                        <span class="d-flex align-item-center"><i class="fa-solid fa-upload"></i>&nbsp;&nbsp;&nbsp;Upload File</span>         
                    </a> 
					<!--begin::Items-->
					<div class="m-0">
                        <!--begin::Item-->
						<a href="<?=site_url('HR/Memo')?>" class="btn btn-sm px-3 border border-transparent btn-color-gray-700 btn-active-color-gray-900">               
                            <i class="fa-solid fa-envelope-open-text"></i>&nbsp;&nbsp;All Memos          
						</a>  
						<!--end::Item-->
                        <!--begin::Item-->
						<a href="<?=site_url('HR/new-employee')?>" class="btn btn-sm px-3 border border-transparent btn-color-gray-700 btn-active-color-gray-900">               
                            <i class="fa-solid fa-user-tie"></i>&nbsp;&nbsp;New Employee           
						</a>  
						<!--end::Item-->
                        <?php if(session()->get('role')=="Administrator"){ ?>
						<!--begin::Item-->
						<a href="<?=site_url('HR/new-account')?>" class="btn btn-sm px-3 border border-transparent btn-color-gray-700 btn-active-color-gray-900">               
                            <i class="fa-solid fa-user-plus"></i>&nbsp;New Account           
						</a>  
						<!--end::Item-->
                        <!--begin::Item-->
						<a href="<?=site_url('HR/users')?>" class="btn btn-sm px-3 border border-transparent btn-color-gray-700 btn-active-color-gray-900">               
                            <i class="fa-solid fa-users"></i>&nbsp;All Accounts          
						</a>  
						<!--end::Item-->
                        <?php } ?> 
					</div>
					<!--end::Items-->   
				</div>
				<!--end::Filter-->
					
				<!--begin::Main menu-->
				<div 
					class="
						menu-sidebar 
						menu 
						menu-fit 
						menu-column 
						menu-rounded 
						menu-title-gray-700 
						menu-icon-gray-700
						menu-arrow-gray-700 
						fw-semibold 
						fs-6 
						align-items-stretch 
						flex-grow-1  
					" 
					id="#kt_app_sidebar_menu" 
					data-kt-menu="true"
					data-kt-menu-expand="true">        
                    <div  class="menu-item py-1" ><!--begin:Menu content-->
                        <div  class="menu-content" >
                            <div class="separator separator-dashed"></div>
                        </div><!--end:Menu content-->
                    </div><!--end:Menu item-->
                </div>
				<!--end::Menu-->
			</div>
			<!--end::Sidebar wrapper-->    
		</div>
		<!--end::Sidebar-->                
            <!--begin::Main-->
            <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                <!--begin::Content wrapper-->
                <div class="d-flex flex-column flex-column-fluid">
                <!--begin::Toolbar-->
                <div id="kt_app_toolbar" class="app-toolbar  pt-10 mb-0 ">                        
                    <!--begin::Toolbar container-->
                    <div id="kt_app_toolbar_container" class="app-container  container-fluid d-flex align-items-stretch ">
                        <!--begin::Toolbar wrapper-->
                        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                            <!--begin::Page title-->
                            <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                                <!--begin::Title-->
                                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                                Employee Profile
                                </h1>
                                <!--end::Title-->
                                <!--begin::Breadcrumb-->
                                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                                    <!--begin::Item-->
                                    <li class="breadcrumb-item text-muted">
                                        <a href="<?=site_url('HR/overview')?>" class="text-muted text-hover-primary">
                                        E201                           
                                        </a>
                                    </li>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <li class="breadcrumb-item">
                                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                                    </li>
                                    <!--end::Item-->                 
                                    <!--begin::Item-->
                                    <li class="breadcrumb-item text-muted">
                                        Employee Profile                                   
                                    </li>
                                    <!--end::Item-->                     
                                </ul>
                                <!--end::Breadcrumb-->
                            </div>
                            <!--end::Page title-->   
                            <!--begin::Actions-->
                            <div class="d-flex align-items-center gap-2 gap-lg-3">
                                <button type="button" class="btn btn-sm btn-flex btn-primary promote">
                                    <i class="fa-solid fa-ranking-star"></i>&nbsp;Promote
                                </button> 
                                <button type="button" class="btn btn-sm btn-flex btn-primary addWork">
                                    <i class="fa-solid fa-file-circle-plus"></i>&nbsp;Work History
                                </button> 
                                <a href="<?=site_url('HR/edit-employee/')?><?php echo $employee['Token'] ?>" class="btn btn-sm btn-flex btn-primary">
                                    <i class="fa-regular fa-pen-to-square"></i>&nbsp;Edit Profile
                                </a>          
                            </div>
                            <!--end::Actions-->
                        </div>
                    <!--end::Toolbar wrapper-->        
                    </div>
                    <!--end::Toolbar container-->
                </div>
                <!--end::Toolbar-->  
                <!--begin::Content-->
                <div id="kt_app_content" class="app-content  flex-column-fluid" >
                    <!--begin::Content container-->
                    <div id="kt_app_content_container" class="app-container container-fluid ">
                        <form method="POST" class="d-flex flex-column flex-lg-row gap-3 w-100" id="frmEmployee">
                            <?php if($employee):?>
                            <input type="hidden" name="employeeID" value="<?php echo $employee['employeeID'] ?>"/>
                            <div class="col-12">
                                <div class="d-flex flex-column flex-lg-row gap-5 w-100">
                                    <div class="col-lg-3">
                                        <div class="card card-flush py-4">
                                            <div class="card-header">
                                                <div class="card-title"><h2>Profile</h2></div>
                                            </div>
                                            <div class="card-body text-center pt-0">
                                                <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                                                    <!--begin::Preview existing avatar-->
                                                    <div class="image-input-wrapper w-150px h-150px" style="background-image: url('/Profile/<?php echo $employee['Photo'] ?>')"></div>
                                                    <!--end::Preview existing avatar-->

                                                    <!--begin::Label-->
                                                    <label class="bg-body shadow">
                                                    </label>
                                                    <!--end::Label-->
                                                </div>
                                                <!--end::Image input-->
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="card card-flush py-4">
                                            <div class="card-header">
                                                <div class="card-title"><h2>Employee Movement</h2></div>
                                            </div>
                                            <div class="card-body pt-0">
                                                <?php foreach($job as $row): ?>
                                                <div class="justify-content-between mb-4">
                                                    <h4 class="fw-bold"><?php echo $row->Title ?></h4>
                                                    <div class="fw-semibold">
                                                    <?php echo $row->Date ?>
                                                    </div>
                                                </div>
                                                <div class="separator separator-dashed"></div>
                                                <br/>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="card card-flush py-4">
                                            <div class="card-header">
                                                <div class="card-title"><h2>Government Records</h2></div>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class="form w-100">
                                                    <div class="fv-row mb-4">
                                                        <span class="menu-title">SSS No</span>
                                                        <input type="text" name="sss_number" id="sss_number" value="<?php echo $employee['SSS'] ?>" class="form-control bg-transparent"/> 
                                                        <span class="text-danger"><?=isset($validation)? display_error($validation,'sss_number') : '' ?></span>
                                                    </div>
                                                    <div class="fv-row mb-4">
                                                        <span class="menu-title">PAG-IBIG No</span>
                                                        <input type="text" name="pagibig_number" id="pagibig_number" value="<?php echo $employee['HDMF'] ?>" class="form-control bg-transparent"/> 
                                                        <span class="text-danger"><?=isset($validation)? display_error($validation,'pagibig_number') : '' ?></span>
                                                    </div>
                                                    <div class="fv-row mb-4">
                                                        <span class="menu-title">PhilHealth No</span>
                                                        <input type="text" name="ph_number" id="ph_number" value="<?php echo $employee['PhilHealth'] ?>" class="form-control bg-transparent"/> 
                                                        <span class="text-danger"><?=isset($validation)? display_error($validation,'ph_number') : '' ?></span>
                                                    </div>
                                                    <div class="fv-row mb-4">
                                                        <span class="menu-title">TIN No</span>
                                                        <input type="text" name="tin_number" id="tin_number" value="<?php echo $employee['TIN'] ?>" class="form-control bg-transparent"/> 
                                                        <span class="text-danger"><?=isset($validation)? display_error($validation,'tin_number') : '' ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="card card-flush py-4">
                                            <div class="card-header">
                                                <div class="card-title"><h2>Personal Details</h2></div>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class="form gap-1">
                                                    <div class="fv-row mb-4">
                                                        <div class="d-flex flex-column flex-lg-row gap-3">
                                                            <div class="col-lg-4">
                                                                <span  class="menu-title" >Surname</span>
                                                                <input type="text" name="surname" id="surname" value="<?php echo $employee['Surname'] ?>" class="form-control bg-transparent"/> 
                                                                <span class="text-danger"><?=isset($validation)? display_error($validation,'surname') : '' ?></span>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <span  class="menu-title" >Firstname</span>
                                                                <input type="text" name="firstname" id="firstname" value="<?php echo $employee['Firstname'] ?>" class="form-control bg-transparent"/> 
                                                                <span class="text-danger"><?=isset($validation)? display_error($validation,'firstname') : '' ?></span>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                <span  class="menu-title" >M.I.</span>
                                                                <input type="text" name="middlename" id="middlename" value="<?php echo $employee['MI'] ?>" class="form-control bg-transparent"/> 
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <span  class="menu-title" >Suffix</span>
                                                                <input type="text" name="suffix" id="suffix" value="<?php echo $employee['Suffix'] ?>" class="form-control w-175px bg-transparent"/> 
                                                            </div>
                                                        </div>   
                                                    </div>
                                                    <div class="fv-row mb-4">
                                                        <div class="d-flex flex-wrap gap-5">
                                                            <div class="fv-row w-100 flex-md-root">
                                                                <span class="menu-title">Company ID</span>
                                                                <input type="text" name="CompanyID" id="CompanyID" value="<?php echo $employee['CompanyID'] ?>" class="form-control bg-transparent"/>    
                                                            </div>
                                                            <div class="fv-row w-100 flex-md-root">
                                                                <span class="menu-title">Contact No</span>
                                                                <input type="phone" name="contactNo" id="contactNo" value="<?php echo $employee['ContactNo'] ?>" class="form-control bg-transparent"/> 
                                                            </div>
                                                            <div class="fv-row w-100 flex-md-root">
                                                                <span class="menu-title">Email Address</span>
                                                                <input type="email" name="email" id="email" value="<?php echo $employee['EmailAddress'] ?>" class="form-control bg-transparent"/> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="fv-row mb-4">
                                                        <div class="d-flex flex-wrap gap-5">
                                                            <div class="fv-row w-100 flex-md-root">
                                                                <span class="menu-title">Marital Status</span>
                                                                <input type="text" name="maritalStatus" id="maritalStatus" value="<?php echo $employee['MaritalStatus'] ?>" class="form-control bg-transparent"/>    
                                                                <span class="text-danger"><?=isset($validation)? display_error($validation,'maritalStatus') : '' ?></span>
                                                            </div>
                                                            <div class="fv-row w-100 flex-md-root">
                                                                <span class="menu-title">Date of Birth</span>
                                                                <input type="text" name="dob" id="dob" value="<?php echo $employee['BirthDate'] ?>" class="form-control bg-transparent"/> 
                                                                <span class="text-danger"><?=isset($validation)? display_error($validation,'dob') : '' ?></span>
                                                            </div>
                                                            <div class="fv-row w-100 flex-md-root">
                                                                <span class="menu-title">Place of Birth</span>
                                                                <input type="text" name="place_of_birth" id="place_of_birth" value="<?php echo $employee['PlaceOfBirth'] ?>" class="form-control bg-transparent"/> 
                                                                <span class="text-danger"><?=isset($validation)? display_error($validation,'place_of_birth') : '' ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="fv-row mb-4">
                                                        <span  class="menu-title" >Permanent Address</span>
                                                        <textarea id="address" class="form-control" name="address" class="min-h-200px mb-2"><?php echo $employee['Address'] ?></textarea>
                                                        <span class="text-danger"><?=isset($validation)? display_error($validation,'address') : '' ?></span>
                                                    </div>
                                                    <div class="fv-row mb-4">
                                                        <div class="d-flex flex-wrap gap-5">
                                                            <div class="fv-row w-100 flex-md-root">
                                                                <span class="menu-title">Date Hired</span>
                                                                <input type="text" name="date_hired" id="dateHired" value="<?php echo $employee['DateHired'] ?>" class="form-control bg-transparent"/> 
                                                                <span class="text-danger"><?=isset($validation)? display_error($validation,'date_hired') : '' ?></span>
                                                            </div>
                                                            <div class="fv-row w-100 flex-md-root">
                                                                <span class="menu-title">Salary Grade</span>
                                                                <input type="text" name="salary_grade" id="salary_grade" value="<?php echo $employee['SalaryGrade'] ?>" class="form-control bg-transparent"/> 
                                                                <span class="text-danger"><?=isset($validation)? display_error($validation,'salary_grade') : '' ?></span>
                                                            </div>
                                                            <div class="fv-row w-100 flex-md-root">
                                                                <span class="menu-title">Employment Status</span>
                                                                <input type="text" name="employeeStatus" id="employeeStatus" value="<?php echo $employee['EmployeeStatus'] ?>" class="form-control bg-transparent"/> 
                                                                <span class="text-danger"><?=isset($validation)? display_error($validation,'employeeStatus') : '' ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="fv-row mb-4">
                                                        <div class="d-flex flex-wrap gap-5">
                                                            <div class="fv-row w-100 flex-md-root">
                                                                <span  class="menu-title" >Father's Name</span>
                                                                <input type="text" name="fathersName" id="fathersName" value="<?php echo $employee['Guardian1'] ?>" class="form-control bg-transparent"/> 
                                                                <span class="text-danger"><?=isset($validation)? display_error($validation,'fathersName') : '' ?></span>
                                                            </div>
                                                            <div class="fv-row w-100 flex-md-root">
                                                                <span  class="menu-title" >Mother's Name</span>
                                                                <input type="text" name="mothersName" id="mothersName" value="<?php echo $employee['Guardian2'] ?>" class="form-control bg-transparent"/> 
                                                                <span class="text-danger"><?=isset($validation)? display_error($validation,'mothersName') : '' ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="fv-row mb-4" style="display:none;" id="ifMarried">
                                                        <div class="d-flex flex-wrap gap-5">
                                                            <div class="fv-row w-100 flex-md-root">
                                                                <span  class="menu-title" >Spouse's Name</span>
                                                                <input type="text" name="spouseName" id="spouseName" value="<?php echo $employee['Spouse'] ?>" class="form-control bg-transparent"/> 
                                                                <span class="text-danger"><?=isset($validation)? display_error($validation,'spouseName') : '' ?></span>
                                                            </div>
                                                            <div class="fv-row w-100 flex-md-root">
                                                                <span  class="menu-title" >Date of Birth</span>
                                                                <input type="text" name="spouseDOB" id="spouseDOB" value="<?php echo $employee['SpouseDOB'] ?>" class="form-control bg-transparent"/> 
                                                                <span class="text-danger"><?=isset($validation)? display_error($validation,'spouseDOB') : '' ?></span>
                                                            </div>
                                                            <div class="fv-row w-100 flex-md-root">
                                                                <span  class="menu-title" >No. of Children</span>
                                                                <input type="text" name="children" id="children" value="<?php echo $employee['Children'] ?>" class="form-control bg-transparent"/> 
                                                                <span class="text-danger"><?=isset($validation)? display_error($validation,'children') : '' ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="fv-row mb-4">
                                                        <span  class="menu-title" >Educational Attainment</span>
                                                        <input type="text" class="form-control bg-transparent" value="<?=$employee['Education']?>"/>
                                                        <span class="text-danger"><?=isset($validation)? display_error($validation,'education') : '' ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="card card-flush py-4">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h2>Employment History</h2>
                                                </div>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class="form w-100">
                                                    <div class="fv-row mb-4">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped">
                                                                <thead>
                                                                    <th class="text-white">Company/Designation</th>
                                                                    <th class="text-white">From</th>
                                                                    <th class="text-white">To</th>
                                                                    <th class="text-white w-100px">More</th>
                                                                </thead>
                                                                <tbody id="tblhistory">
                                                                    <tr>
                                                                        <td colspan="4" class="text-center">No Record(s)</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif;?>
                        </form>
                    </div>
                <!--end::Content-->	
                </div>
                <!--end::Content wrapper-->                          
            </div>
            <!--end:::Main-->   
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Page-->
</div>
<!--end::App-->		
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="fa-solid fa-arrow-up"></i>
		</div>
		<!--end::Scrolltop-->
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
				<script src="<?=base_url('assets/plugins/global/plugins.bundle.js')?>"></script>
				<script src="<?=base_url('assets/js/scripts.bundle.js')?>"></script>
			<!--end::Global Javascript Bundle-->

		<!--begin::Vendors Javascript(used for this page only)-->
				<script src="<?=base_url('assets/plugins/custom/datatables/datatables.bundle.js')?>"></script>
			<!--end::Vendors Javascript-->

		<!--begin::Custom Javascript(used for this page only)-->
				<script src="<?=base_url('assets/js/widgets.bundle.js')?>"></script>
				<script src="<?=base_url('assets/js/custom/widgets.js')?>"></script>
			<!--end::Custom Javascript-->
	    <!--end::Javascript-->
        <div class="modal fade" id="modalWork" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header pb-0 border-0">
                        <h2 class="modal-title">Add Employment History</h2>
                    </div>
                    <!--begin::Modal header-->

                    <!--begin::Modal body-->
                    <div class="modal-body">
                        <form method="POST" class="form w-100" id="frmHistory">
                            <input type="hidden" name="employeeID" value="<?php echo $employee['employeeID'] ?>"/>
                            <div class="fv-row mb-4">
                                <span class="menu-title">Designation/Position</span>
                                <input type="text" name="job" class="form-control bg-transparent"/>
                            </div>
                            <div class="fv-row mb-4">
                                <span class="menu-title">Company</span>
                                <input type="text" name="company" class="form-control bg-transparent"/>
                            </div>
                            <div class="fv-row mb-4">
                                <span class="menu-title">Company Address</span>
                                <textarea id="address" class="form-control" name="company_address" class="min-h-200px mb-2"></textarea>
                            </div>
                            <div class="fv-row mb-4">
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
                                        <span class="menu-title">From</span>
                                        <input type="date" name="fromdate" class="form-control bg-transparent"/>
                                    </div>
                                    <div class="fv-row w-100 flex-md-root">
                                        <span class="menu-title">To</span>
                                        <input type="date" name="todate" class="form-control bg-transparent"/>
                                    </div>
                                </div>
                            </div>
                            <div class="fv-row mb-4" id="btnAction">
                                <button type="submit" class="btn btn-primary" id="Add"><i class="fa-solid fa-circle-plus"></i>&nbsp;Add Entry</button>
                            </div>
                            <div class="fv-row mb-4" id="btnProgress" style="display:none;">
                                <button type="button" class="btn btn-primary">
                                    Please wait...    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--edit form-->
        <div class="modal fade" id="modalEditWork" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header pb-0 border-0">
                        <h2 class="modal-title">Edit Employment History</h2>
                    </div>
                    <!--begin::Modal header-->

                    <!--begin::Modal body-->
                    <div class="modal-body">
                        <div id="output"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- promote -->
        <div class="modal fade" id="modalPromote" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header pb-0 border-0">
                        <h2 class="modal-title">Promotion</h2>
                    </div>
                    <!--begin::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body">
                        <form method="POST" class="form w-100" id="frmPromote">
                            <input type="hidden" name="employeeID" value="<?php echo $employee['employeeID'] ?>"/>
                            <div class="fv-row mb-4">
                                <span class="menu-title">Designation/Position</span>
                                <input type="text" name="job" class="form-control bg-transparent"/>
                            </div>
                            <div class="fv-row mb-4">
                                <span class="menu-title">Date Effectivity</span>
                                <input type="date" name="date" class="form-control bg-transparent"/>
                            </div>
                            <div class="fv-row mb-4" id="btnConfirm">
                                <button type="submit" class="btn btn-primary" id="Add"><i class="fa-solid fa-circle-plus"></i>&nbsp;Save Entry</button>
                            </div>
                            <div class="fv-row mb-4" id="btnLoad" style="display:none;">
                                <button type="button" class="btn btn-primary">
                                    Please wait...    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </button>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function()
            {
                listWork();var val = $('#maritalStatus').val();if(val==="Married"||val==="Single with Children"){$('#ifMarried').slideDown();}else{$('#ifMarried').slideUp();}
            });
            $(document).on('click','.addWork',function(){$('#modalWork').modal('show');});
            $(document).on('click','.edit',function(){var val = $(this).val();$.ajax({url:"<?=site_url('fetch-data')?>",method:"GET",data:{value:val},success:function(response){$('#modalEditWork').modal('show');$('#output').html(response);}});});
            $('#maritalStatus').change(function(){var val = $(this).val();if(val==="Married"||val==="Single with Children"){$('#ifMarried').slideDown();}else{$('#ifMarried').slideUp();}});
            function listWork()
            {
                $('#tblhistory').html("<tr><td colspan='4' class='text-center'>Loading...</td></tr>");
                var user = "<?=$employee['Token']?>";
                $.ajax({
                    url:"<?=site_url('list-work')?>",method:"GET",
                    data:{user:user},
                    success:function(response)
                    {
                        if(response==="")
                        {
                            $('#tblhistory').html("<tr><td colspan='4' class='text-center'>No Record(s)</td></tr>");
                        }
                        else
                        {
                            $('#tblhistory').html(response);
                        }
                    }
                });
            }

            $(document).on('click','.save',function(e){
                e.preventDefault();
                var data = $('#editHistory').serialize();
                document.getElementById('btnSave').style="display:none";
                document.getElementById('btnLoading').style="display:block";
                $.ajax({
                    url:"<?=site_url('update-data')?>",method:"POST",
                    data:data,success:function(response)
                    {
                        document.getElementById('btnSave').style="display:block";
                        document.getElementById('btnLoading').style="display:none";
                        if(response=="success")
                        {
                            Swal.fire({
                                title: "Great!",
                                text: "Successfully applied changes",
                                icon: "success"
                            });
                            listWork();
                            $('#modalEditWork').modal('hide'); 
                        }
                        else
                        {
                            Swal.fire({
                                title: "Warning!",
                                text: response,
                                icon: "warning"
                            });
                        }
                    }
                });
            });

            $('#frmHistory').on('submit',function(e){
                e.preventDefault();
                var data = $(this).serialize();
                document.getElementById('btnAction').style="display:none";
                document.getElementById('btnProgress').style="display:block";
                $.ajax({
                    url:"<?=site_url('save-work')?>",method:"POST",
                    data:data,success:function(response)
                    {
                        document.getElementById('btnAction').style="display:block";
                        document.getElementById('btnProgress').style="display:none";
                        if(response=="success")
                        {
                            Swal.fire({
                                title: "Great!",
                                text: "Successfully added",
                                icon: "success"
                            });
                            listWork();
                            $('#frmHistory')[0].reset();
                            $('#modalWork').modal('hide'); 
                        }
                        else
                        {
                            Swal.fire({
                                title: "Warning!",
                                text: response,
                                icon: "warning"
                            });
                        }
                    }
                });
            });

            $(document).on('click','.promote',function(){
                Swal.fire({
                    icon:"question",
                    title: "Do you want to promote this employee?",
                    showDenyButton: true,
                    confirmButtonText: "Yes",
                    denyButtonText: "Cancel"
                    }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        //open dialog box
                        $('#modalPromote').modal('show');
                    } 
                });
            });

            $('#frmPromote').on('submit',function(e){
                e.preventDefault();
                var data = $(this).serialize();
                document.getElementById('btnConfirm').style="display:none";
                document.getElementById('btnLoad').style="display:block";
                $.ajax({
                    url:"<?=site_url('save-promotion')?>",method:"POST",
                    data:data,success:function(response)
                    {
                        document.getElementById('btnConfirm').style="display:block";
                        document.getElementById('btnLoad').style="display:none";
                        if(response=="success")
                        {
                            Swal.fire({
                                title: "Great!",
                                text: "Successfully added",
                                icon: "success"
                            }); 
                            location.reload();
                        }
                        else
                        {
                            Swal.fire({
                                title: "Warning!",
                                text: response,
                                icon: "warning"
                            });
                        }
                    }
                });
            });
        </script>
    </body>
    <!--end::Body-->
</html>