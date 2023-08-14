<div id="preloader">
    <div id="loader"></div>
</div>
@push('js')
    <style>
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #loader {
            width: 50px;
            height: 50px;
            border: 5px solid #ccc;
            border-top-color: #0B5ED7;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
