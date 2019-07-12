console.log("Main js");
toastr.options = {
    "closeButton": true,
    "debug": false,
    "preventDuplicates": true,
    "positionClass": "toast-bottom-right",
    "onclick": null,
    "showDuration": "400",
    "hideDuration": "1000",
    "timeOut": "8000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};
function showResponse(data){
    console.log("Show response",data);
    switch (data.response.status) {
        case 1:
            toastr.success("SUCCESS");
            setTimeout(function () {
                location.reload();
            },800)
            break;
        case 0:
            toastr.error(data.response.error);
            break;
    }
}
