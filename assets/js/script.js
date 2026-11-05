document.addEventListener('DOMContentLoaded', function () {
    const flash = document.querySelector('.alert');
    if (flash) {
        setTimeout(() => {
            if (flash && flash.classList.contains('show')) {
                flash.classList.remove('show');
                flash.classList.add('hide');
            }
        }, 3000);
    }
});

function showConfirmDelete(message, callback) {
    Swal.fire({
        title: 'Konfirmasi',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#123d72',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}
