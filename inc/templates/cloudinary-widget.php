<script src="https://widget.cloudinary.com/v2.0/global/all.js" type="text/javascript"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var myWidget = cloudinary.createUploadWidget({
            cloudName: 'flaunt-your-site'
        }, (error, result) => {
            if (!error && result && result.event === "success") {
                console.log('Done! Here is the image info: ', result.info);
            }
        })

        document.getElementById("upload_widget").addEventListener("click", function() {
            myWidget.open();
        }, false);
    });
</script>