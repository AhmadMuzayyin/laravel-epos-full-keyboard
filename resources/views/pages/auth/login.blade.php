@extends('app')
@section('content')
    <div class="container align-items-center  justify-content-center mt-5">
        <br> <br> <br>

        <div class="d-flex justify-content-center">
            <div class="card text-center mt-5 border-warning shadow shadow-lg" style="max-width: 30rem;">
                <div class="card-header">
                    <h1 class="font-bold">Welcome to Wartel</h1>
                    <h5>PP AL - IBROHIMIY</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" autocomplete="off"
                                autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-outline-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
