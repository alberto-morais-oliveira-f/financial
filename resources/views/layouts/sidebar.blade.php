<li class="menu menu-heading">
    <div class="heading">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="feather feather-minus">
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>FINANCEIRO</span>
    </div>
</li>
<li class="menu {{ Request::routeIs(
        'master.monthly_payment',
        'financial.payments.index',
        'financial.incomes.index',
        'financial.expenses.index',
        'financial.wallets.index',
        'financial.categories.index',
        'financial.transactions.index'
        ) ? 'active' : '' }}">
    <a href="#recebimentos" data-bs-toggle="collapse"
       role="button" aria-expanded="{{ Request::routeIs(
        'master.monthly_payment',
         'financial.payments.index',
          'financial.incomes.index',
           'financial.expenses.index',
           'financial.wallets.index',
           'financial.categories.index',
           'financial.transactions.index'
           ) ? 'true' :
                       'false'}}"
       class="dropdown-toggle">
        <div class="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                 stroke-linejoin="round" class="feather feather-dollar-sign">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
            <span>Gestão Financeira</span>
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
                         'master.payment.report',
                         'financial.payments.index',
                         'financial.incomes.index',
                         'financial.expenses.index',
                         'financial.wallets.index',
                         'financial.categories.index',
                         'financial.transactions.index'
                         ) ? 'show' : ''}}"
        id="recebimentos">
        <li class="{{ Request::routeIs('financial.wallets.index') ? 'active' : '' }}">
            <a href="{{ route('financial.wallets.index') }}"> Carteiras </a>
        </li>
        <li class="{{ Request::routeIs('financial.categories.index') ? 'active' : '' }}">
            <a href="{{ route('financial.categories.index') }}"> Categorias </a>
        </li>
        <li class="{{ Request::routeIs('financial.transactions.index') ? 'active' : '' }}">
            <a href="{{ route('financial.transactions.index') }}"> Lançamentos </a>
        </li>
        <li class="{{ Request::routeIs('financial.payments.index') ? 'active' : '' }}">
            <a href="{{route('financial.payments.index')}}"> Pagamentos </a>
        </li>
        <li class="{{ Request::routeIs('financial.incomes.index') ? 'active' : '' }}">
            <a href="{{route('financial.incomes.index')}}"> Receitas </a>
        </li>
        <li class="{{ Request::routeIs('financial.expenses.index') ? 'active' : '' }}">
            <a href="{{route('financial.expenses.index')}}"> Despesas </a>
        </li>
        <li class="{{ Request::routeIs('financial.suppliers.index') ? 'active' : '' }}">
            <a href="{{route('financial.suppliers.index')}}"> Fornecedores </a>
        </li>
    </ul>
</li>

<li class="menu {{ Request::routeIs('master.financial-reports.*', 'financial.reports*') ? 'active' : '' }}">
    <a href="#financial-reports" data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ Request::routeIs('master.financial-reports.*', 'financial.reports*') ? 'true' : 'false' }}"
       class="dropdown-toggle">
        <div class="text-ellipsis" title="Relatórios Financeiro">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-bar-chart-2">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg>
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
    <ul class="collapse submenu list-unstyled {{ Request::routeIs('master.financial-reports.*', 'financial.reports*')
     ? 'show' : '' }}"
        id="financial-reports">
        <li class="{{ Request::routeIs('master.financial-reports.monthly-billing') ? 'active' : '' }}">
            <a href="{{ route('master.financial-reports.monthly-billing') }}"> Faturamento Mensal </a>
        </li>
        <li class="{{ Request::routeIs('financial.reports.dre') ? 'active' : '' }}">
            <a href="{{ route('financial.reports.dre') }}"> Relatório DRE </a>
        </li>
    </ul>
</li>
