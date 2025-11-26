import Swal from 'sweetalert2';

export const showMessage = (msg = '', type = 'success') => {
  Swal.mixin({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 3000,
    customClass: { container: 'toast' },
  }).fire({ icon: type, title: msg, padding: '10px 20px' })
}

