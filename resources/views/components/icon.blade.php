@php
if ($type == 'edit')
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" width="'.$size.'rem" height="'.$size.'rem" class="'.$class.'"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg>';
else if ($type == 'error_icon') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#EA3323" class="'.$class.'"><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>';
} else if ($type == 'restore') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" class="'.$class.'"><path d="M480-560 320-400l56 56 64-64v168h80v-168l64 64 56-56-160-160Zm-280-80v440h560v-440H200Zm0 520q-33 0-56.5-23.5T120-200v-499q0-14 4.5-27t13.5-24l50-61q11-14 27.5-21.5T250-840h460q18 0 34.5 7.5T772-811l50 61q9 11 13.5 24t4.5 27v499q0 33-23.5 56.5T760-120H200Zm16-600h528l-34-40H250l-34 40Zm264 300Z"/></svg>';
} else if ($type =='arrow_back') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>';
} else if ($type == 'send') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="M120-160v-240l320-80-320-80v-240l760 320-760 320Z"/></svg>';
} else if ($type == 'bell') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80ZM320-280h320v-280q0-66-47-113t-113-47q-66 0-113 47t-47 113v280Z"/></svg>';
} else if ($type == 'read') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>';
} else if ($type == 'open_in_new') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h560v-280h80v280q0 33-23.5 56.5T760-120H200Zm188-212-56-56 372-372H560v-80h280v280h-80v-144L388-332Z"/></svg>';
} else if ($type == 'unread') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="m381-240 424-424-57-56-368 367-169-170-57 57 227 226Zm0 113L42-466l169-170 170 170 366-367 172 168-538 538Z"/></svg>';
}
@endphp

{!! $svgIcon !!}
