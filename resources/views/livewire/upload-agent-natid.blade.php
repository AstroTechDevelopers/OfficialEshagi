<div>
    @if ($kyc->national_id1_stat == true)
        <div class="row">
            <div class="col-lg-6">
                <img src="{{asset($kyc->national_id1)}}" id="uploadedID" height="120" />
            </div>
        </div>
    @else
        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-6">
                    <div wire:loading wire:target="national_id1">Uploading National ID...</div>
                    <div class="form-group">
                        <input type="file" class="form-control" wire:model="national_id1" required/>
                        @error('national_id1') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <br>
                    @if ($national_id1)
                        National ID Preview:
                        <img src="{{ $national_id1->temporaryUrl() }}" height="120">
                        <br><br>
                    @endif
                </div>
                <div class="col-lg-6">

                </div>
            </div>
            <button type="submit" class="btn btn-primary">Confirm & Upload</button>
        </form>
    @endif
</div>
