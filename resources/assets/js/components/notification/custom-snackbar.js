window.add_notification_options = function add_notification_options(options) {
    Snackbar.show(options);
}

window.add_notification_primary = function (text) {
    let options = {
        text: text,
        pos: 'top-right',
        actionText: "Fechar",
        actionTextColor: '#fff',
        backgroundColor: '#4361ee'
    };
    Snackbar.show(options);
}

window.add_notification_info = function (text) {
    let options = {
        text: text,
        pos: 'top-right',
        actionTextColor: '#fff',
        actionText: "Fechar",
        backgroundColor: '#2196f3'
    };
    Snackbar.show(options);
}

window.add_notification_danger = function (text) {
    let options = {
        text: text,
        pos: 'top-right',
        actionTextColor: '#fff',
        actionText: "Fechar",
        backgroundColor: '#e7515a'
    };
    Snackbar.show(options);
}

window.add_notification_warning = function (text) {
    let options = {
        text: text,
        pos: 'top-right',
        actionText: "Fechar",
        actionTextColor: '#fff',
        backgroundColor: '#e2a03f'
    };
    Snackbar.show(options);
}

window.add_notification_secondary = function (text) {
    let options = {
        text: text,
        pos: 'top-right',
        actionText: "Fechar",
        actionTextColor: '#fff',
        backgroundColor: '#805dca'
    };
    Snackbar.show(options);
}

window.add_notification_dark = function (text) {
    let options = {
        text: text,
        pos: 'top-right',
        actionText: "Fechar",
        actionTextColor: '#fff',
        backgroundColor: '#3b3f5c'
    };
    Snackbar.show(options);
}