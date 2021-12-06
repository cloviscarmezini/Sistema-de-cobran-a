require('./bootstrap');

import Swal from 'sweetalert2';

window.deleteConfirm = function(formId)
{
    Swal.fire({
        icon: 'warning',
        text: 'Deseja excluir?',
        showCancelButton: true,
        confirmButtonText: 'Deletar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#e3342f',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
