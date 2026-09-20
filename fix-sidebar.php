<?php
$file = 'resources/views/layouts/partials/sapta-sidebar.blade.php';
$content = file_get_contents($file);

$oldCode = '@php $unread = auth()->user()->unreadNotifications->count(); @endphp
                @if($unread > 0)
                    <span style="margin-left:auto; background:#dc2626; color:#fff; font-size:0.65rem; font-weight:800; padding:0.15rem 0.45rem; border-radius:999px;">{{ $unread }}</span>
                @endif';

$newCode = '@auth
                    @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                    @if($unread > 0)
                        <span style="margin-left:auto; background:#dc2626; color:#fff; font-size:0.65rem; font-weight:800; padding:0.15rem 0.45rem; border-radius:999px;">{{ $unread }}</span>
                    @endif
                @endauth';

$content = str_replace($oldCode, $newCode, $content);

file_put_contents($file, $content);
echo "Fixed sidebar\n";
