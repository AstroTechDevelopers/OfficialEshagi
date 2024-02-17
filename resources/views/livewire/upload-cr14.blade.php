<div>
    @if ($kyc->cr14_stat == true)
        <div class="row">
            <div class="col-lg-6">
                <img src="{{asset($kyc->cr14)}}" id="uploadedCr14" height="120" />
            </div>
        </div>
    @else
        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <div wire:loading wire:target="cr14">Uploading CR14...</div>
            <div class="form-group">
                <input type="file" class="form-control" wire:model="cr14" required/>
                @error('cr14') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
            @if ($cr14)
                CR14 Preview:
                <img src="{{ $cr14->temporaryUrl() }}" height="120">
                <br><br>
            @endif

            <button type="submit" class="btn btn-primary">Confirm & Upload</button>
        </form>
    @endif
</div>
