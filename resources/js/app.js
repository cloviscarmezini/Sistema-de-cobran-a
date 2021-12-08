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
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

window.handlePayInstallment = function(formId)
{
    Swal.fire({
        icon: 'warning',
        text: 'Deseja efetuar o pagamento?',
        showCancelButton: true,
        confirmButtonText: 'Pagar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#167ac6',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
