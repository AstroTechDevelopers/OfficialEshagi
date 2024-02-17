<div>
    @if ($kyc->pphoto1_stat == true || $kyc->pphoto2_stat == true )
        <div class="row">
            <div class="col-lg-6">
                <img src="{{asset($kyc->pphoto1)}}" id="uploadedPhoto" height="120" />
            </div>

            <div class="col-lg-6">
                <img src="{{asset($kyc->pphoto2)}}" id="uploadedPhoto2" height="120" />
            </div>
        </div>
    @else
        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-6">
                    <div wire:loading wire:target="pphoto1">Uploading Passport Photo 1...</div>
                    <div class="form-group">
                        <input type="file" class="form-control" wire:model="pphoto1" required/>
                        @error('pphoto1') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <br>
                    @if ($pphoto1)
                        Passport Photo #1 Preview:
                        <img src="{{ $pphoto1->temporaryUrl() }}" height="120">
                        <br><br>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div wire:loading wire:target="pphoto2">Uploading Passport Photo 2...</div>
                    <div class="form-group">
                        <input type="file" class="form-control" wire:model="pphoto2" />
                        @error('pphoto2') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <br>
                    @if ($pphoto2)
                        Passport Photo #2 Preview:
                        <img src="{{ $pphoto2->temporaryUrl() }}" height="120">
                        <br><br>
                    @endif

                </div>
            </div>
            <button type="submit" class="btn btn-primary">Confirm & Upload</button>
        </form>
    @endif
</div>
