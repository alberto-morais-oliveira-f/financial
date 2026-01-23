<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">
        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="{{getRouterValue()}}dashboard/analytics">
                        <img src="{{Vite::asset('resources/images/b9.png')}}" class="logo-light navbar-logo-g" alt="logo">
                        <img src="{{Vite::asset('resources/images/b9.png')}}" class="logo-dark navbar-logo-g" alt="logo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a href="{{getRouterValue()}}dashboard/analytics" class="nav-link"> E.G.J </a>
                </div>
            </div>
            <div class="nav-item sidebar-toggle">
                <div class="btn-toggle sidebarCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevrons-left">
                        <polyline points="11 17 6 12 11 7"></polyline>
                        <polyline points="18 17 13 12 18 7"></polyline>
                    </svg>
                </div>
            </div>
        </div>
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu {{ Request::routeIs("{$prefix}.dashboard") ? 'active' : '' }}">
                <a href="{{route("$prefix.dashboard")}}"
                   aria-expanded="{{ Request::routeIs('dashboard') ? 'true' : 'false' }}"
                   class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>
            @if(__isMaster())
                <li class="menu {{ Request::routeIs("$prefix.students", 'master.student-types') ? 'active' : '' }}">
                    <a href="#menu-students" data-bs-toggle="collapse"
                       role="button" aria-expanded="false"
                       class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-users">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Alunos</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{
                        Request::routeIs(
                         "$prefix.students",
                         'master.student-types',
                         ) ? 'show' : ''}}"
                        id="menu-students">
                        <li class="{{ Request::routeIs("$prefix.students") ? 'active' : '' }}">
                            <a href="{{route("$prefix.students")}}"> Listar </a>
                        </li>
                        <li class="{{ Request::routeIs('master.student-types') ? 'active' : '' }}">
                            <a href="{{ route('master.student-types') }}"> Criar Tipo </a>
                        </li>
                    </ul>
                </li>

                <li class="menu {{
    Request::routeIs("{$prefix}.reward.index") ||
    Request::routeIs("{$prefix}.exam-student.index")
    ? 'active' : '' }}">
                    <a href="#graduation" data-bs-toggle="collapse"
                       aria-expanded="{{
    Request::routeIs("{$prefix}.reward.index") ||
    Request::routeIs("{$prefix}.exam-student.index")
    ? 'true' : 'false' }}" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-shield">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                            <span>Graduação</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{
    Request::routeIs("{$prefix}.reward.index") ||
    Request::routeIs("{$prefix}.exam-student.index")
    ? 'show' : '' }}"
                        id="graduation"
                    >
                        @if(__isMaster())
                            <li class="{{ Request::routeIs("{$prefix}.reward.index") ? 'active' : '' }}">
                                <a href="{{route("{$prefix}.reward.index")}}">Config Graduações </a>
                            </li>
                            <li class="{{ Request::routeIs("{$prefix}.belt-exam") ? 'active' : '' }}">
                                <a href="{{route("{$prefix}.belt-exam")}}">Exams de Faixa </a>
                            </li>
                            <li class="{{ Request::routeIs("{$prefix}.exam-student.index") ? 'active' : '' }}">
                                <a href="{{route("{$prefix}.exam-student.index")}}">Exame/Aluno </a>
                            </li>
                        @endif
                        @if(!__isMaster())
                        @endif
                    </ul>
                </li>
                <li class="menu {{ Request::routeIs("{$prefix}.categories") || Request::routeIs("{$prefix}.positions") || Request::routeIs("{$prefix}.positions.create") ? 'active' : '' }}">
                    <a href="#positions" data-bs-toggle="collapse"
                       aria-expanded="{{ Request::routeIs("{$prefix}.categories") || Request::routeIs("{$prefix}.positions") || Request::routeIs("{$prefix}.positions.create") ? 'true' : 'false'}}"
                       class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-book-open">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                            <span>Posições</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::routeIs("{$prefix}.categories") || Request::routeIs("{$prefix}.positions") || Request::routeIs("{$prefix}.positions.create") ? 'show' : ''}}"
                        id="positions">
                        <li class="{{ Request::routeIs('categories') ? 'active' : '' }}">
                            <a href="{{route('master.categories')}}"> Categorias </a>
                        </li>
                        <li class="{{ Request::routeIs('master.positions') ? 'active' : '' }}">
                            <a href="{{route('master.positions')}}"> Lista de posições </a>
                        </li>
                        <li class="{{ Request::routeIs('master.positions.create') ? 'active' : '' }}">
                            <a href="{{route('master.positions.create')}}"> Nova posição </a>
                        </li>
                    </ul>
                </li>

                <li class="menu {{Request::routeIs("{$prefix}modality.index") ||
                    Request::routeIs("{$prefix}.training.create") ||
                    Request::routeIs("{$prefix}.schedule") ||
                    Request::routeIs("{$prefix}.timeline") ||
                    Request::routeIs("presence")
                    ? 'active' : '' }}">
                    <a href="#training" data-bs-toggle="collapse"
                       aria-expanded="{{Request::routeIs("{$prefix}.modality.index") ||
                        Request::routeIs("{$prefix}.training.create") ||
                        Request::routeIs("{$prefix}.schedule") ||
                        Request::routeIs("{$prefix}.timeline") ||
                        Request::routeIs("presence")
                        ? 'true' : 'false' }}" class="dropdown-toggle"
                    >
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-award">
                                <circle cx="12" cy="8" r="7"></circle>
                                <polyline points="8.21 13.89 7 23 12 17 17 23 15.79 13.88"></polyline>
                            </svg>
                            <span>Treino</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{
                        Request::routeIs("{$prefix}.modality.index") ||
                        Request::routeIs("{$prefix}.training.create") ||
                        Request::routeIs("{$prefix}.schedule") ||
                        Request::routeIs("{$prefix}.timeline") ||
                        Request::routeIs("presence")
                        ? 'show' : '' }}" id="training">
                        <li class="{{ Request::routeIs("{$prefix}.modality.index") ? 'active' : '' }}">
                            <a href="{{route("{$prefix}.modality.index")}}"> Modalidades </a>
                        </li>
                        <li class="{{ Request::routeIs("{$prefix}.training.create") ? 'active' : '' }}">
                            <a href="{{route("{$prefix}.training.create")}}"> Treino </a>
                        </li>
                        <li class="{{ Request::routeIs("{$prefix}.timeline") ? 'active' : '' }}">
                            <a href="{{route("{$prefix}.timeline")}}"> Cronograma </a>
                        </li>
                    </ul>
                </li>
                <li class="menu menu-heading">
                    <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>FINANCEIRO</span>
                    </div>
                </li>
                @if(!__isMaster())
                    <li class="menu {{ Request::routeIs('payment') ? 'active' : '' }}">
                        <a href="{{route("payment")}}" aria-expanded="{{ Request::routeIs('payment') ? 'true' : 'false' }}"
                           class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-credit-card">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                <span>Pagamento</span>
                            </div>
                        </a>
                    </li>
                @endif
                <li class="menu {{ Request::routeIs("{$prefix}.payment.plans", 'master.monthly_payment', 'master.payment.subscription*') ? 'active' : '' }}">
                    <a href="#recebimentos" data-bs-toggle="collapse"
                       role="button" aria-expanded="{{ Request::routeIs("{$prefix}.payment.plans", 'master.monthly_payment', 'master.payment.subscription*') ? 'true' :
                       'false'}}"
                       class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-dollar-sign">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            <span>Recebimentos</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{
                        Request::routeIs(
                         'master.monthly_payment',
                         'master.payment.plans',
                         'master.payment.subscription*',
                         'master.payment.report'
                         ) ? 'show' : ''}}"
                        id="recebimentos">
                        <li class="{{ Request::routeIs('master.payment.plans') ? 'active' : '' }}">
                            <a href="{{route('master.payment.plans')}}"> Planos </a>
                        </li>
                        <li class="{{ Request::routeIs('master.payment.subscription*') ? 'active' : '' }}">
                            <a href="{{route('master.payment.subscription')}}"> Assinaturas </a>
                        </li>
                    </ul>
                </li>

                <li class="menu {{ Request::routeIs('master.financial-reports.*') ? 'active' : '' }}">
                    <a href="#financial-reports" data-bs-toggle="collapse"
                       role="button" aria-expanded="{{ Request::routeIs('master.financial-reports.*') ? 'true' : 'false' }}"
                       class="dropdown-toggle">
                        <div class="text-ellipsis" title="Relatórios Financeiro">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                            <span>Relatórios Financeiro</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::routeIs('master.financial-reports.*') ? 'show' : '' }}"
                        id="financial-reports">
                        <li class="{{ Request::routeIs('master.financial-reports.monthly-billing') ? 'active' : '' }}">
                            <a href="{{ route('master.financial-reports.monthly-billing') }}"> Faturamento Mensal </a>
                        </li>
                    </ul>
                </li>
            @endif
            <li class="menu menu-heading">
                <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span>CONFIGURAÇÕES</span>
                </div>
            </li>
            @if(__isMaster())
                <li class="menu
                 {{
    Request::routeIs("{$prefix}.category-products.index") ||
    Request::routeIs("{$prefix}.products.index")
    ? 'active' : '' }}">
                    <a href="#config-shop" data-bs-toggle="collapse"
                       aria-expanded="{{
    Request::routeIs("{$prefix}.category-products.index") ||
    Request::routeIs(".{$prefix}.products.index")
    ? 'true' : 'false' }}" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-shopping-cart">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <span>Conf Loja</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{
    Request::routeIs("{$prefix}.category-products.index") ||
    Request::routeIs("{$prefix}.products.index")
    ? 'show' : '' }}"
                        id="config-shop">
                        <li class="{{ Request::routeIs("{$prefix}.category-products.index") ? 'active' : '' }}">
                            <a href="{{route("{$prefix}.category-products.index")}}"> Categorias </a>
                        </li>
                        <li class="{{ Request::routeIs("{$prefix}.products.index") ? 'active' : '' }}">
                            <a href="{{route("{$prefix}.products.index")}}"> Produtos </a>
                        </li>
                    </ul>
                </li>
            @endif
            <li class="menu {{ (in_array($catName, ['shop.products', 'shop.orders'])) ? 'active' : '' }}">
                <a href="#shop" data-bs-toggle="collapse"
                   aria-expanded="{{ (in_array($catName, ['shop.products', 'shop.orders'])) ? 'true' : 'false' }}"
                   class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-shopping-bag">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <span>Loja</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ ($catName === 'shop') ? 'show' : '' }}"
                    id="shop">
                    <li class="{{ Request::routeIs('shop.products') ? 'active' : '' }}">
                        <a href="{{route("shop.products")}}"> Produtos </a>
                    </li>
                    @if(__isMaster())
                        <li class="{{ Request::routeIs('shop.orders') ? 'active' : '' }}">
                            <a href="{{route("shop.orders")}}">Pedidos </a>
                        </li>
                    @endif
                    @if(!__isMaster())
                        <li class="{{ Request::routeIs('shop.orders') ? 'active' : '' }}">
                            <a href="{{route("shop.orders")}}"> Meus Pedidos </a>
                        </li>
                    @endif
                </ul>
            </li>
            @if(__isMaster())
                <li class="menu {{ Request::routeIs("{$prefix}.location.index") ? 'active' : '' }}">
                    <a href="#configurations" data-bs-toggle="collapse"
                       aria-expanded="{{ Request::routeIs("{$prefix}.location.index") ? 'true' : 'false' }}"
                       class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-settings">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            <span>Configurações</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::routeIs("{$prefix}.location.index") ? 'show' : '' }}"
                        id="configurations">
                        <li class="{{ Request::routeIs("{$prefix}.location.index") ? 'active' : '' }}">
                            <a href="{{route("{$prefix}.location.index")}}"> Locais </a>
                        </li>
                        <li>
                            <a href="{{route("{$prefix}.notice")}}">
                                <span>Avisos</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </nav>
</div>
