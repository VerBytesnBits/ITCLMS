import './session-alert';
import './bootstrap';

['unit.created', 'unit.updated', 'unit.deleted'].forEach(event => {
    window.Echo.channel('units')
        .listen(`.${event}`, () => {
            window.location.reload();
        });
});




  

