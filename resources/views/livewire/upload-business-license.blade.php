<div>
    @if ($kyc->bus_licence_stat == true)
        <div class="row">
            <div class="col-lg-6">
                <img src="{{asset($kyc->bus_licence)}}" id="uploadedBusLicence" height="120" />
            </div>
        </div>
    @else
        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <div wire:loading wire:target="bus_licence">Uploading Business Licence...</div>
            <div class="form-group">
                <input type="file" class="form-control" wire:model="bus_licence" required/>
                @error('bus_licence') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
            @if ($bus_licence)
                Business Licence Preview:
                <img src="{{ $bus_licence->temporaryUrl() }}" height="120">
                <br><br>
            @endif

            <button type="submit" class="btn btn-primary">Confirm & Upload</button>
        </form>
    @endif
</div>
