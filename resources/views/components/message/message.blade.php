@if($message = flash()->get())
    <div class="app_flach_message flashMessage__wrap">
        <div class="{{ $message->class() }} flashMessage">
            <div class="message">
                <div class="message__icon"></div>
                <div class="message__body">{!! $message->message() !!}</div>
                <div class="btn-close app_f_message_close"></div>
            </div>
        </div>
    </div>
@endif
