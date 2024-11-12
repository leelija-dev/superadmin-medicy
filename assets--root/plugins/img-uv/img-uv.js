document.addEventListener("DOMContentLoaded", () => {
    var readURL = (input) => {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = (e) => {
                document.querySelector(".img-uv-view").src = e.target.result;
            }

            reader.readAsDataURL(input.files[0]);
            document.querySelector("#img-uv-input").classList.remove("d-none");
        }
    };
    document.querySelector("#img-uv-input").addEventListener("change", function() {
        readURL(this);
    });
});