<?php
$seenIcon = (!!$seen ? 'check-double' : 'check');
$timeAndSeen = "<span data-time='$created_at' class='message-time'>
        ".($isSender ? "<span class='fas fa-$seenIcon' seen'></span>" : '' )." <span class='time'>$timeAgo</span>
    </span>";
?>

<div class="message-card @if($isSender) mc-sender @endif" data-id="{{ $id }}">
    {{-- Delete Message Button --}}
    @if ($isSender)
        <div class="actions">
            <i class="fas fa-trash delete-btn" data-id="{{ $id }}"></i>
        </div>
    @endif

    {{-- Message Card Content --}}
    <div class="message-card-content">
        {{-- Check for Location Message --}}
        @if (!empty($message) && Str::startsWith($message, 'https://www.google.com/maps?q='))
            <div class="message location-message">
                <a href="{{ $message }}" target="_blank" class="location-link">
                    📍 View Location on Google Maps
                </a>
                {!! $timeAndSeen !!}
            </div>
        @elseif (@$attachment->type != 'image' || $message)
            <div class="message">
                {!! ($message == null && $attachment != null && @$attachment->type != 'file') ? $attachment->title : nl2br($message) !!}
                {!! $timeAndSeen !!}
                {{-- If attachment is a file --}}
                @if(@$attachment->type == 'file')
                    <a href="{{ route(config('chatify.attachments.download_route_name'), ['fileName'=>$attachment->file]) }}" class="file-download">
                        <span class="fas fa-file"></span> {{$attachment->title}}
                    </a>
                @endif
            </div>
        @endif

        {{-- If attachment is an image --}}
        @if(@$attachment->type == 'image')
            <div class="image-wrapper" style="text-align: {{$isSender ? 'end' : 'start'}}">
                <div class="image-file chat-image" style="background-image: url('{{ asset('storage/attachments/' . $attachment->file) }}')">
                    <div>{{ $attachment->title }}</div>
                </div>
                <div style="margin-bottom:5px">
                    {!! $timeAndSeen !!}
                </div>
            </div>
        @endif
    </div>
</div>
