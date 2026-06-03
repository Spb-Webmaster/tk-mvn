@if ($errors->any())
    <div class="app_flach_message flashMessage__wrap">
        <div class="class__alert flashMessage">
            <div class="message">
                <div class="message__icon"></div>
                <div class="message__body">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
                <div class="btn-close app_f_message_close"></div>
            </div>
        </div>
    </div>
@endif
