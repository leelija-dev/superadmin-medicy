<!-- This is the image section which is using to show the uploaded image using it's default class -->
<img class="img-uv-view" src="{default image path}">

<!-- This is the input of image which is also recognized by it's default id -->
<input type="file" style="display:none;" id="img-uv-input" accept="image/*">
<label for="img-uv-input">Recent Photo</label>


<!-- Bellow is an simple example of upload and view image -->
<div class="row w-100 m-0 mb-2">
    <div class="col-6 col-sm-auto mb-3">
        <div class="mx-auto" style="width: 130px;">
            <div class="d-flex justify-content-center align-items-center rounded"
                style="height: 130px; background-color: rgb(233, 236, 239);">
                <img class="img-uv-view" src="{default image path}">
            </div>
        </div>
    </div>
    <div class="col-6 d-flex align-items-center">
        <div class="input-group mt-2">
            <input type="file" class="d-none" id="img-uv-input" name="profile-picture" accept="image/*">
            <label class="input-group-text btn btn-primary rounded" for="img-uv-input">
                Recent Photo
            </label>
        </div>
    </div>
</div>
