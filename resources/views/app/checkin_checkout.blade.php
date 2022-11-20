@extends('layouts.master')
@section('title', 'Check In - Check Out')

@section('content')

    <div class="d-flex justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <p>QR Code</p>
                        <img src="data:image/png;base64, {!! base64_encode(
                            QrCode::format('png')->size(100)->generate($hash_value),
                        ) !!} ">
                        <p class="text-muted mt-3">Please scan QR code to check in - check out.</p>
                    </div>
                    <hr class="my-4">
                    <div class="text-center">
                        <p>Pin Code</p>
                        <input type="text" name="mycode" id="pincode-input1">
                        <p class="text-muted mt-3">Please enter PIN code to check in - check out.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('#pincode-input1').pincodeInput({
                inputs: 6,
                complete: function(value, e, errorElement) {
                    console.log("code entered: " + value);

                    $.ajax({
                        url: "/checkin_checkout/store",
                        type: "POST",
                        data: {
                            'pin_code': value
                        },
                        dataType: 'json',
                        success: function(response) {

                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal
                                        .stopTimer)
                                    toast.addEventListener('mouseleave', Swal
                                        .resumeTimer)
                                }
                            });

                            if (response.status === 'success') {
                                Toast.fire({
                                    icon: 'success',
                                    title: response.message
                                })
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: response.message
                                })
                            }

                            $('.pincode-input-text').val('');
                            $('.pincode-input-text').eq(0).focus();
                        }
                    })

                    // $(errorElement).html("I'm sorry, but the code not correct");
                }
            });

            $('.pincode-input-text').eq(0).focus();
        })
    </script>
@endsection
