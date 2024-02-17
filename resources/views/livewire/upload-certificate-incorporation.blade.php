<div>
    @if ($kyc->cert_incorp_stat == true)
        <div class="row">
            <div class="col-lg-6">
                <img src="{{asset($kyc->cert_incorp)}}" id="uploadedCert" height="120" />
            </div>
        </div>
    @else
        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <div wire:loading wire:target="certificate">Uploading Certificate...</div>
            <div class="form-group">
                <input type="file" class="form-control" wire:model="certificate" required/>
                @error('certificate') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
            @if ($certificate)
                Certificate Preview:
                <img src="{{ $certificate->temporaryUrl() }}" height="120">
                <br><br>
            @endif

            <button type="submit" class="btn btn-primary">Confirm & Upload</button>
        </form>
    @endif
</div>
