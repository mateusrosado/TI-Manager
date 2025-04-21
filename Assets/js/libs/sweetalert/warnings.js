function warnings(typeIcon, textTitle, timer = 6000) {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        showCloseButton: true,
        timer: timer,
    timerProgressBar: true,
    });
    Toast.fire({
        icon: typeIcon,
        title: textTitle,
    });
}

function doubleConfirmComplete(form, text, type, buttonText, buttonColor) {
    Swal.fire({
        text: text,
        icon: type,
        showCancelButton: true,
        confirmButtonColor: buttonColor[0] || '#DC3545',
        cancelButtonColor: buttonColor[1] || '#6C757D',
        confirmButtonText: buttonText[0] || 'Sim, Remover',
        cancelButtonText: buttonText[1] || 'Não, Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $(`#${form}`).submit();
        }
    });
}
