import './bootstrap';
document.addEventListener('DOMContentLoaded', function() {
    if (window.Echo) {
        var channel = window.Echo.channel('my-channel');
        channel.listen('.my-event', function(data) {
            alert(JSON.stringify(data));
        });
    } else {
        console.error('Echo is not defined');
    }
});
