<div id="uploadimageModal" class="modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload & Crop Image</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="display: block;">
                    <div class="col-md-12">
                        <div class="col-md-8 text-center">
                            <div id="image_demo" class="col-md-12"></div>
                        </div>
                        <div class="col-md-6">
                            <br/>
                            <br/>
                            <br/>
                            <button class="btn btn-success crop_image">Crop & Upload Image</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){

        $image_crop = $('#image_demo').croppie({
            enableExif: true,
            viewport: {
                width:400,
                height:400,
                type:'square' //circle
            },
            boundary:{
                width:500,
                height:500
            }
        });

        $('.attach_image').on('change', function(){
            let reader = new FileReader();
            var file = this.files[0]; // Get your file here
            var fileTypes = ['jpg', 'jpeg', 'png'];
            var fileExt = file.type.split('/')[1]; // Get the file extension
            if (fileTypes.indexOf(fileExt) === -1) {
                swal("Only png,jpg file formats are allowed ");
                $('.attach_image').val('');
                return;
            }
            reader.onload = function (event) {
                $image_crop.croppie('bind', {
                    url: event.target.result
                }).then(function(){
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
            $('#uploadimageModal').modal('show');
        });

        $('.crop_image').click(function(event){
            $image_crop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(response){
                if (!response){
                    return;
                }
                $.ajax({
                    url:"logic.php",
                    type: "POST",
                    data:{
                        "image": response,
                        "update_profile":true,
                        "id":<?php echo isset($_GET['id']) ? json_encode(Core::urlValueEncrypt($_GET['id'])) : 0?>,
                        "_token":<?php echo json_encode($token);?>
                    },

                    success:function(data){
                        data = JSON.parse(data);
                        if (data.success) {
                            $('#uploadimageModal').modal('hide');
                            $(".uploaded_profile_pic").val(data.image);
                            //let is_admin = <?php //echo isset($is_Admin) ? 1 : 0; ?>//;
                            // if( !is_admin ) {
                                // swal("Profile photo update", "Updating profile photo was successful", "success").then((value) => {
                                //     location = data.url;
                                // });
                            // }else{
                                // swal("Profile photo update", "Updating profile photo was successful", "success").then((value) => {
                                //     window.location.reload();
                                // });
                            // }
                        }else{
                            swal("Profile photo upload","Uploading profile photo failed","error");
                        }
                    }
                });
            })
        });

    });
</script>