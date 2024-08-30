import Echo from 'laravel-echo';
 


window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'c6e92c66b1405e5e075d',
    cluster: 'eu',
    forceTLS: true
  });

var channel = Echo.channel('my-channel');
channel.listen('.my-event', function(data) {
  alert(JSON.stringify(data));
});
