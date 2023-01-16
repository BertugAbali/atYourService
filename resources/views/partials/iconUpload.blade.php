<!-- Upload Icon Modal -->
<div class="modal fade" id="icon" tabindex="-1" aria-labelledby="iconLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="iconLabel">Change Profile Icon</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('user/upload/'.Auth::user()->id) }}" enctype="multipart/form-data" id="upload-image" method="POST">
                @csrf
                <div class="modal-body text-center">
                    <label class="form-label" for="image">Upload New Icon</label>
                    <input type="file" class="form-control" id="image" name="image"/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" value="Save Changes" class="btn btn-primary">Save changes</button>
                    </div>
            </form>
        </div>
    </div>
</div>