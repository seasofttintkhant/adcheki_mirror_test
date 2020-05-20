require('./bootstrap');
require('admin-lte/dist/js/adminlte.min.js');
require('admin-lte/plugins/pace-progress/pace.min.js');
window.Swal = require('admin-lte/plugins/sweetalert2/sweetalert2.min.js');

$(document).ready(function () {
    let swal = document.getElementById('swal');
    if (swal) {
        Swal.fire(
            `${swal.getAttribute('data-title')}`,
            `${swal.getAttribute('data-message')}`,
            `${swal.getAttribute('data-icon')}`
        );
    }

    $('.remove').on('click', function (e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let content = $(this).attr('data-content');
        let type = $(this).attr('data-type');
        Swal.fire({
            title: 'Are you sure?',
            text: `You want to remove ${content}!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.value) {
                axios.delete(`/backend/${type}/${id}`)
                    .then(res => {
                        if (res.data.status === 'success') {
                            Swal.fire(
                                'Removed!',
                                `${res.data.message}`,
                                `${res.data.status}`
                            ).then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            })
                        } else {
                            Swal.fire(
                                'Remove Failed!',
                                `Cannot remove the ${content}.`,
                                'error'
                            )
                        }
                    }).catch(e => console.log(e));
            }
        })
    });
});