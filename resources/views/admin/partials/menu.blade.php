      <!-- Navbar -->
      <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
          <div class="container-xxl">

              <!--  Brand demo (display only for navbar-full and hide on below xl) -->
              <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
                  <a href="{{ url('/admin') }}" class="app-brand-link gap-2">
                      <img src="{{ url('assets/img/logo-zazara.png') }}" alt="Hypercode" style="height:25px;">
                      <span class="app-brand-text demo menu-text fw-bold text-heading">Zazara-ERP</span>
                  </a>

                  <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                      <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
                  </a>
              </div>

              <!-- ! Not required for layout-without-menu -->
              <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none  ">
                  <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                      <i class="bx bx-menu bx-md"></i>
                  </a>
              </div>

              <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                  <ul class="navbar-nav flex-row align-items-center ms-auto">
                      <!-- User -->
                      <li class="nav-item navbar-dropdown dropdown-user dropdown">
                          <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                              data-bs-toggle="dropdown">
                              <div class="avatar avatar-online">
                                  @empty(auth()->user()->avatar)
                                      <img src="{{ asset('avatar/default.png') }}" alt
                                          class="w-px-40 h-auto rounded-circle" />
                                  @else
                                      <img src="{{ asset('avatar/' . auth()->user()->avatar) }}" alt
                                          class="w-px-40 h-auto rounded-circle" />
                                      @endif
                                  </div>
                              </a>

                              @auth
                                  <ul class="dropdown-menu dropdown-menu-end">
                                      <li>
                                          <a class="dropdown-item" href="#">
                                              <div class="d-flex">
                                                  <div class="flex-shrink-0 me-3">
                                                      <div class="avatar avatar-online">
                                                          @empty(auth()->user()->avatar)
                                                              <img src="{{ asset('avatar/default.png') }}" alt
                                                                  class="w-px-40 h-auto rounded-circle" />
                                                          @else
                                                              <img src="{{ asset('avatar/' . auth()->user()->avatar) }}" alt
                                                                  class="w-px-40 h-auto rounded-circle" />
                                                              @endif
                                                          </div>
                                                      </div>
                                                      <div class="flex-grow-1">
                                                          <span class="fw-semibold d-block">{{ auth()->user()->name }}</span>
                                                          <small class="text-muted">{{ auth()->user()->email }}</small>
                                                      </div>
                                                  </div>
                                              </a>
                                          </li>
                                          <li>
                                              <div class="dropdown-divider"></div>
                                          </li>
                                          <li>
                                              <a class="dropdown-item" href="{{ url('change_password') }}">
                                                  <i class="bx bxs-lock-alt me-2"></i>
                                                  <span class="align-middle">Change Password</span>
                                              </a>
                                          </li>
                                          <li>
                                              <a class="dropdown-item" href="{{ url('logout') }}">
                                                  <i class="bx bx-power-off me-2"></i>
                                                  <span class="align-middle">Log Out</span>
                                              </a>
                                          </li>
                                      </ul>
                                  @endauth
                              </li>
                              <!--/ User -->
                          </ul>
                      </div>
                  </div>
              </nav>
              <!-- / Navbar -->
              <!-- END: Navbar-->
