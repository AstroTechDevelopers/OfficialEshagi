<div>
    @if ($kyc->cr6_stat == true)
        <div class="row">
            <div class="col-lg-6">
                <img src="{{asset($kyc->cr6)}}" id="uploadedCr6" height="120" />
            </div>
        </div>
    @else
        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <div wire:loading wire:target="cr6">Uploading CR6...</div>
            <div class="form-group">
                <input type="file" class="form-control" wire:model="cr6" required/>
                @error('cr6') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
            @if ($cr6)
                CR6 Preview:
                <img src="{{ $cr6->temporaryUrl() }}" height="120">
                <br><br>
            @endif

            <button type="submit" class="btn btn-primary">Confirm & Upload</button>
        </form>
    @endif
</div>
