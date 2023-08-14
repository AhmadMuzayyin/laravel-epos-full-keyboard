<div>
    <nav class="nav nav-tabs p-3">
        @php
            $isActive = request()->segment(1)
        @endphp
        <a href="{{ route('dashboard') }}" class="btn btn-outline-success {{ $isActive == 'dashboard' ? 'active' : '' }}">
            <i class="bi bi-house-check-fill"></i> Home
        </a>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-success {{ $isActive == 'transactions' ? 'active' : '' }} mx-2">
            <i class="bi bi-currency-exchange"></i> Transactions
        </a>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-success {{ $isActive == 'categories' ? 'active' : '' }}">
            <i class="bi bi-list-columns-reverse"></i> Category
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-outline-success {{ $isActive == 'products' ? 'active' : '' }} mx-2">
            <i class="bi bi-upc-scan"></i> Products
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-outline-success {{ $isActive == 'purchase' ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Purchase
        </a>

        <form action="{{ route('logout') }}" method="POST" class="text-right">
            @csrf
            <button type="submit" class="btn btn-outline-success mx-2">
                <i class="bi bi-power"></i> Log out
            </button>
        </form>
    </nav>
</div>
