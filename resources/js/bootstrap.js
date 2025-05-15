import axios from 'axios';

// Toastr
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

//IMask
import IMask from 'imask';

window.axios = axios;
window.IMask = IMask;
window.toastr = toastr;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
