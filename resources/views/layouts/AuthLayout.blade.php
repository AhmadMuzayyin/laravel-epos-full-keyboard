<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<div>
    <div class="container mt-5">

        <div class="card border-warning shadow shadow-lg">
            <div class="card-body">
                <x-navbar />

                <!-- Page Content -->
                <div id="container">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let defaultUri = ''
        $('.url').click(function(e) {
            e.preventDefault()
            let uri = $(this).attr('href')
            loadPage(uri);
            $('.url').removeClass('active');
            $(this).addClass('active');
        })
    });

    function loadPage(url) {
        $.get(url, function(response) {
            $('#container').html(response);
        });
    }
</script>