flatpickr("#periodFilter", {
    mode: "range",
    dateFormat: "Y-m-d",
});

function reloadPageAfterDelay(delay) {
    setTimeout(function() {
        location.reload();
    }, delay);
}